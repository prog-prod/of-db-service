<?php

use App\Contracts\MetaTagGeneratorServiceInterface;
use App\Facades\Helper;
use App\Models\OfTag;
use App\Models\OfUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $path = storage_path('result.csv');
        $data = array_map('str_getcsv', file($path));
        $keys = array_column($data, 0);
        foreach ($keys as $string) {
            $columns = explode(';', $string);
            $key = str_replace(['/', '\\', '/', '%20', '%2F', '_'], ['', '','', '-', '', '-'], explode(';', $columns[0])[0]);
            $key = preg_replace('/%[0-9a-fA-F]{2}/', '', $key);
            if(preg_match('/^\d+$/', $key) || preg_match('/\./', $key)){
                echo "\n\tskipping key: ".$key;
                continue;
            }
            try {
                $modelsCount = OfUser::search($key)->paginate();
                $name = Helper::getNameFromKey($key);

                $results = [
                    'key' => $key,
                    'name' => $name,
                    'results' => $modelsCount->total(),
                    'traffic' => $columns[1],
                ];

                OfTag::query()->create($results);
            } catch (QueryException $exception){
                $reason = $exception->getMessage();
                if(str_contains($reason, 'Duplicate entry')){
                    $reason = 'Duplicate key';
                }

                echo "\n\tskipping key: ".$key. "(".$reason.")";
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        OfTag::query()->truncate();
    }
};
