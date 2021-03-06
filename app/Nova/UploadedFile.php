<?php

namespace App\Nova;

use App\Jobs\ParseAttemptHtmlDocument;
use App\Nova\Actions\ImportQuestionsFromUploadedFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class UploadedFile extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\UploadedFile::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'filename';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'filename'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Filename')->exceptOnForms(),
            Code::make('Parsed', 'filename')
                ->language('php')
                ->onlyOnDetail()
                ->stacked()
                ->resolveUsing(function($filename) {
                    $a = (new ParseAttemptHtmlDocument($this->resource))->handle();

                    return json_encode($a, JSON_PRETTY_PRINT);
                }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            ImportQuestionsFromUploadedFile::make()->canRun(function() {
                return true;
            }),
        ];
    }
}
