<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParserStatus extends Model
{

    protected $fillable = [
      'of_sign_version',
      'total_parsed',
      'good_parsed',
      'bad_parsed',
      'prepared_performers',
      'parsed_performers',
      'parsed_regulars',
      'prepared_regulars',
      'should_stop_parser',
      'not_found_sequence',
      'max_user_id_to_parse',
      'min_user_id_to_parse',
      'chunk_size',
      'speed',
      'average_response_time_sec',
      'total_progress_percent',
      'time_sec',
      'finished_chunks_count',
      'in_progress_chunks_count',
      'total_chunks_count',
      'used_proxies_count',
      'used_proxies_list',
    ];

    protected $casts = [
        'used_proxies_list' => 'json'
    ];
}
