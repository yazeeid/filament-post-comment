<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class CommentsExport implements FromCollection,WithMapping,WithHeadings
{

    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(public Collection $records)
    {

    }
    public function collection()
    {
        return $this->records;
    }

    public function map($comment): array
    {
        return [
            $comment->post->description,
            $comment->comment,
            $comment->user->name,
            $comment->created_at,
            $comment->updated_at,

        ];

    }

    public function headings(): array
    {
        return [
            'post',
            'comment',
            'commentCreator',
            'created at',
            'updated at',
        ];

    }


}
