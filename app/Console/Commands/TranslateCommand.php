<?php

namespace App\Console\Commands;

use Google\Cloud\Translate\V3\TranslateClient;
use Google\Cloud\Translate\V3\TranslationServiceClient;
use Illuminate\Console\Command;
use Spatie\Fork\Fork;

class TranslateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:translate-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . base_path("ab-ocula-eb698d7a975c.json"));

        $translationClient = new TranslationServiceClient();
        $data = json_decode(\File::get(base_path("51k.json")), true);
        $newContent = [];
        $collection = collect($data);
        // dd($collection->slice(0, 50)->all());
        // file_put_contents(base_path("51k.json"), json_encode($collection->values(), JSON_PRETTY_PRINT));

        foreach ($collection->chunk(50) as $idx => $items) {
            $this->info("Number of current collection: " . ($idx + 1));

            $cbs = [];

            foreach ($items as $item) {
                $cbs[] = function () use ($translationClient, $item, &$newContent) {
                    $response = $translationClient->translateText(
                        [
                            $item['instruction'],
                            $item['input'] ?: '-',
                            $item['output']
                        ],
                        'uz',
                        TranslationServiceClient::locationName('664307719776', 'global')
                    )->getTranslations();
                    

                    return [
                        'instruction' => $response[0]->getTranslatedText(),
                        'input' => $item['input'] === "" ? '' : $response[1]->getTranslatedText(),
                        'output' => $response[2]->getTranslatedText()
                    ];
                };
            }

            $results = Fork::new()->run(...$cbs);

            foreach ($results as $idx => $result) {
                $newContent[] = $result;
            }

            file_put_contents(base_path("translated-51k.json"), json_encode($newContent, JSON_PRETTY_PRINT));

            usleep(200);
        }

        return 0;
    }
}