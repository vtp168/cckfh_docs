<?php

namespace App\Repositories\Implementation;

use App\Models\DocumentComments;
use App\Repositories\Implementation\BaseRepository;
use App\Repositories\Contracts\DocumentCommentRepositoryInterface;
use Illuminate\Support\Facades\DB;

//use Your Model

/**
 * Class DocumentCommentRepository.
 */
class DocumentCommentRepository extends BaseRepository implements DocumentCommentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor..
     *
     *
     * @param Model $model
     */


    public static function model()
    {
        return DocumentComments::class;
    }

    public function getDocumentComment($id)
    {
        $query = DocumentComments::select(['documentComments.*', DB::raw("CONCAT(users.firstName,' ', users.lastName) as createdByName,users.id as userId")])
            ->join('users', 'documentComments.createdBy', '=', 'users.id');
        $query = $query->where('documentId', $id);
        $query = $query->orderBy('documentComments.createdAt', 'DESC');
        $results = $query->get();

        return $results;
    }

    public function deleteDocumentComments($id)
    {
        return DocumentComments::destroy($id);
    }
}
