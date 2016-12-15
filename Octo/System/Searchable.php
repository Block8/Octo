<?php

namespace Octo\System;

interface Searchable
{
    /**
     * Gets the search index ID for this model. Usually this would be equivalent to getId();
     * @return int
     */
    public function getSearchId();

    /**
     * Gets the content to add to search for this model.
     * @return string
     */
    public function getSearchContent() : string;
}
