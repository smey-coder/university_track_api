<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assignment;

class CloseExpiredAssignments extends Command
{

    protected $signature = 'assignment:auto-close';


    protected $description =
    'Automatically close expired assignments';


    public function handle()
    {


        $updated = Assignment::where(
                'status',
                'Open'
            )
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            )
            ->update([

                'status'=>'Closed'

            ]);



        $this->info(
            $updated .
            " assignments closed."
        );


    }

}