<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class ReservationCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public Reservation $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $isAdmin = $notifiable->routes['mail'] === Config::get('mail.admin_address');
        $subject = $isAdmin
            ? "【管理者通知】新規予約が入りました (予約ID: {$this->reservation->id})"
            : "【" . config('app.name') . "】ご予約が完了しました";

        $greeting = $isAdmin
            ? "管理者様"
            : "{$this->reservation->name_kanji} 様";

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($isAdmin ? '新しい予約が確定しました。詳細は以下をご確認ください。' : 'この度はご予約いただき、誠にありがとうございます。以下の内容でご予約を承りました。')
            ->line('---')
            ->line('■ 予約情報')
            ->line("予約ID: {$this->reservation->id}")
            ->line("車両名: {$this->reservation->car->name}")
            ->line("利用開始: " . \Carbon\Carbon::parse($this->reservation->start_datetime)->format('Y年m月d日 H:i'))
            ->line("利用終了: " . \Carbon\Carbon::parse($this->reservation->end_datetime)->format('Y年m月d日 H:i'))
            ->line("合計金額: ¥" . number_format($this->reservation->total_price))
            ->line('');

        if ($isAdmin) {
            $message->line('■ お客様情報')
                ->line("氏名: {$this->reservation->name_kanji} ({$this->reservation->name_kana_sei} {$this->reservation->name_kana_mei})")
                ->line("電話番号: {$this->reservation->phone_main}")
                ->line("メールアドレス: {$this->reservation->email}");
        }

        $message->line('---')
            ->line('ご来店を心よりお待ちしております。')
            ->action('マイページで予約を確認', route('mypage'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}