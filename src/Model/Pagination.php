<?php

namespace App\Model;

trait Pagination
{
    public function scopePagination($query, $page, $limit)
    {
        $offset = ($page - 1) * $limit;

        $totalPages = ceil($query->count() / $limit);

        $users = $query->skip($offset)->take($limit)->get();

        $last_offset = ($totalPages - 1) * $limit;
        $previous_offset = ($offset - $page) > 0 ? ($offset - $page) : 0;
        $next_page = $page + 1 > $totalPages ? $last_offset : ($offset + $page);

        $pagination = [
            'totalPages' => $totalPages,
            'page' => $page,
            'list' => $users,
            'last_page' => $totalPages,
            'prev_page' => $page - 1 > 0 ? $page - 1 : 1,
            'next_page' => $page + 1 > $totalPages ? $totalPages : $page + 1,
        ];

        return $pagination;
    }
}