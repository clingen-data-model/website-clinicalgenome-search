<?php

namespace App\Console\Commands;

use App\GpmEvent;
use App\Member;
use App\Panel;
use Illuminate\Console\Command;

class ProcessKafka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:kafka';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $t = [];
        for ($i = 1; $i < 14; $i++) {

            $file = 'file' . $i . '.json';
            $data = file_get_contents(public_path() . '/' . $file);
            $kafkaData = json_decode($data, true);
            usort($kafkaData,function($first,$second) {
                return $first['offset'] > $second['offset'];
            });

            $bar = $this->output->createProgressBar(count($kafkaData));

            foreach( $kafkaData as $data) {

                if ($eventType = data_get($data, 'value.event_type')) {

//                $event = GpmEvent::firstOrNew([
//                    'event' => $eventType
//                ]);

                    $gpm_id = data_get($data, 'value.data.expert_panel.id');


                    if ($gpm_id === '633a2520-7a54-4e73-b04a-e9bd99586a4c') {
                        $t[] = $data;
                    }

                    if ($affiliate_id = data_get($data, 'value.data.expert_panel.affiliation_id')) {
                        $panel = Panel::where('affiliate_id', $affiliate_id)->first();

                        // Check if the same
                    }

                    if (null === $panel) {
                        $panel = Panel::firstOrNew([
                            'gpm_id' => $gpm_id
                        ]);
                    }

                    $panel->title_abbreviated = data_get($data, 'value.data.expert_panel.name');

                    if ($affiliate_id) {
                        $panel->affiliate_id = $affiliate_id;
                    }

                    $panel->gpm_id = data_get($data, 'value.data.expert_panel.id');
                    $panel->affiliate_type = data_get($data, 'value.data.expert_panel.type');

                    $panel->name = data_get($data, 'value.data.expert_panel.name');

                    $panel->save();


                }

                $bar->finish();
            }

            echo $file . ' ' . 'completed' . PHP_EOL;
        }
        dd($t);
    }

    public function addMember($member)
    {
        return Member::find($member['id']);

        if ($email = data_get($member, 'email')) {
            $memberObj = Member::where('email', $email)->first();
        }

        if (null === $memberObj) {
            $memberObj = Member::firstOrNew([
                'gpm_id' => $member['id']
            ]);
        }

        $memberObj->first_name = data_get($member, 'first_name', '');
        $memberObj->last_name = data_get($member, 'last_name', '');
        $memberObj->email= data_get($member, 'email');
        $memberObj->gpm_id = data_get($member, 'id');

        $memberObj->save();

        return $memberObj;
    }
}
