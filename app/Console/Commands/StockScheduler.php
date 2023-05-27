<?php

namespace App\Console\Commands;

use App\Mail\LowStock as MailLowStock;
use App\Notifications\LowStock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Stock;

class StockScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:lowstock_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To pull a list of running low of stock products, send notification to admin at 6 PM daily via email.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stock = Stock::where('quantity', '<=', '100000')->get();
        
        $to_email = "premlamsal2@gmail.com";

        Mail::to($to_email)->send(new MailLowStock($stock));


        $this->info('Daily report has been sent successfully!');
        return 'Daily report has been sent successfully!';
    }
}
