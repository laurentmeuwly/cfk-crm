<?php

namespace App\JsonApi\Contacts;

use App\Models\Contact;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class Adapter extends AbstractAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\Contact(), $paging);
    }

    /*protected function title()
    {
        return $this->belongsTo();
    }*/

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }

    // Created inside the adapter
    /*protected function creating(Contact $contact): void
    {
        //dd('creating...');
        $error = Error::fromArray([
            'title'     => 'The language you want to use is not active',
            'status'    => '402',
        ]);

        throw JsonApiException::make($error);
    }*/
}
