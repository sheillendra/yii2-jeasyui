<?php

namespace sheillendra\jeasyui\components\rest;

use Yii;
use yii\web\Link;

class Serializer extends \yii\rest\Serializer
{
    /**
     * Serializes a pagination into an array.
     * @param Pagination $pagination
     * @return array the array representation of the pagination
     * @see addPaginationHeaders()
     */
    protected function serializePagination($pagination)
    {
        $csrfParam = $this->request->csrfParam;
        $csrfToken = $this->request->csrfToken;

        return [
            $this->linksEnvelope => Link::serialize($pagination->getLinks(true)),
            $this->metaEnvelope => [
                'totalCount' => $pagination->totalCount,
                'pageCount' => $pagination->getPageCount(),
                'currentPage' => $pagination->getPage() + 1,
                'perPage' => $pagination->getPageSize(),
                $csrfParam => $csrfToken,
            ],
        ];
    }
}
