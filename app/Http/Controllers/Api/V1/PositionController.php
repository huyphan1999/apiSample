<?php
namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Position;
use App\Api\Repositories\Contracts\PositionRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected $request;

    protected $positionRepository;

    public function __construct(Request $request,PositionRepository $positionRepository)
    {
        $this->request=$request;
        $this->positionRepository=$positionRepository;
    }

    public function getListPosition()
    {
        $position = $this->positionRepository->all();
        return $position;
    }

    public function create()
    {
        $validator = \Validator::make($this->request->all(),[
            'position'=>'string|required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $position = $this->request->get('position');
        $positionExist = $this->positionRepository->findByField('position', $position)->first();

        if(empty($positionExist))
        {
            $attributes = ['position'=>$position];

            $new_position = $this->positionRepository->create($attributes);
            return $this->successRequest($new_position);
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function delete()
    {
        $validator = \Validator::make($this->request->all(),[
            'position'=>'string|required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $position = $this->request->get('position');
        $positionExist = $this->positionRepository->findByField('position', $position)->first();

        if(!empty($positionExist))
        {
            $positionExist->forceDelete();
            return $this->successRequest('Xóa thành công');
        }
        return $this->errorBadRequest('Lỗi');
    }
}