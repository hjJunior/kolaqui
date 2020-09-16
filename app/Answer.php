<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model {
  use Searchable, HasFactory;

  protected $fillable = [
    'slug', 'content', 'question_id', 'pure_content'
  ];

  protected $hidden = [
    'created_at', 'updated_at', 'question_id',
  ];

  function question() {
    return $this->belongsTo('App\Question');
  }

  function replies() {
    return $this->hasMany('App\Reply');
  }

  function isCorrect() {
    return $this->replies()->where('correct', true)->count() >= 1;
  }

  protected $indexConfigurator = ElasticSearchIndex\AnswersIndexConfigurator::class;

  protected $mapping = [
    'properties' => [
      'content' => [
        'type' => 'text',
        'fields' => [
          'raw' => [
            'type' => 'keyword',
          ]
        ]
      ],
    ]
  ];
}
