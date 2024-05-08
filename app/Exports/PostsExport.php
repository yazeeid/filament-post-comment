<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;


class PostsExport implements FromCollection,WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    public function __construct(public Collection $records)
    {

    }

    public function collection()
    {
        return $this->records;
    }


    public function map($post): array
    {
        return [
            $post->user->name,
            $post->description,
            $post->created_at,
            $post->updated_at,

        ];

    }

    public function headings(): array
    {
        return [
            'postCreator',
            'post',
            'created at',
            'updated at',
        ];

    }



}
