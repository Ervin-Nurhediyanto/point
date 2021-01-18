<?php

namespace App\Console\Commands;

use App\Model\Inventory\Inventory;
use App\Model\Project\Project;
use App\Model\Purchase\PurchaseInvoice\PurchaseInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AlterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:alter-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Temporary';

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
        $projects = Project::where('is_generated', true)->get();
        foreach ($projects as $project) {
            $this->line('Clone '.$project->code);
            // Artisan::call('tenant:database:backup-clone', ['project_code' => strtolower($project->code)]);

            $this->line('Alter '.$project->code);
            config()->set('database.connections.tenant.database', env('DB_DATABASE').'_'.strtolower($project->code));
            
            DB::connection('tenant')->reconnect();
            DB::connection('tenant')->beginTransaction();
            
            $invoices = PurchaseInvoice::all();

            foreach($invoices as $invoice) {
                $aCount = count($invoice->items);
                $bCount = Inventory::where('form_id', '=', $invoice->form->id)->count();
                if ($invoice->form->cancellation_approval_at === null && $aCount < $bCount) {
                    $this->line($invoice->form->number . ' : '. $aCount . ' = ' . $bCount . ' @' . $invoice->form->createdBy->name);
                }
            }

            DB::connection('tenant')->commit();
        }
    }
}
