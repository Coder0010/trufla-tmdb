<?php

namespace App\Traits;

trait PaginationTrait
{
    // current page that api will use
    public $current_page;

    // user selection records type
    public $records_type;

    /**
     * setter for record type for only available types appended in system config
     */
    public function setRecordsType(string $val) : void
    {
        if (in_array($val, config("system.records_type"))) {
            $this->records_type = $val;
        }
    }

    /**
     * getter for record type
     */
    public function getRecordsType() : string
    {
        return $this->records_type;
    }

    /**
     * setter for current page [ for loop starting index is zero and there is no page with zero ]
     */
    public function setCurrentPage(int $val) : void
    {
        $this->current_page = $val;
    }

    /**
     * getter for current page
     */
    public function getCurrentPage() : int
    {
        return $this->current_page;
    }
}
