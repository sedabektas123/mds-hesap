protected function schedule(Schedule $schedule)
{
    $schedule->command('sync:endustriyel-transactions')->hourly(); // Her saat çalıştırır
}

protected function schedule(Schedule $schedule)
{
    $schedule->command('sync:cari-hesap-transactions')->hourly(); // Her saat çalıştır
}
