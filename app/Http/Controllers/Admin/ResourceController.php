<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;
use App\Models\Topic;
use App\Models\ResourceTopic;

class ResourceController extends Controller
{
    //View Dance/Music Type Listing Page
    public function index(){
        $topics = Topic::select('id','parent_id','title')->orderBy('id','desc')->get();
        $resource = Resource::orderBy('created_at','desc')->get();
        return view('admin.resource.index',compact('resource', 'topics'));
    }

    //Filter the dance and music types
    public function ajaxData(Request $request){
        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = Resource::orderBy('resource_order')->whereNull('deleted_at');
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->title;
        })
        ->addColumn('topic_id', function ($Query) {
            $text = "";
            if(isset($Query->topics) && !empty($Query->topics)){
                foreach ($Query->topics as $key => $value) {
                    $text .= '<span class="badge badge-primary mr-1">'.$value->topic->title.'</span>';
                }
            }

            return $text;
        })
        ->addColumn('resource_order', function ($Query) {
            $resourceData = $Query->resource_order ? $Query->resource_order : '-' ;
            return $resourceData;
        })
        ->addColumn('src', function ($Query) {
            $icon = Helper::images(config('constant.resource_url')).$Query->src;
            return "<a class='fancy-pop-image' data-fancybox='images".$Query->id."'  href='".$icon."'><img class='custom-image' src='".$icon."'></a>";
        })
        ->addColumn('status', function ($Query) {
            $text = "<span class='badge badge-danger'><a href='javascript:;' class='type-status' data-active='1' data-id='".$Query->id."'>INACTIVE</a></span>";
            if($Query->status == 1){
                $text = "<span class='badge badge-success'><a href='javascript:;' class='type-status' data-active='0' data-id='".$Query->id."'>ACTIVE</a></span>";
            }
            return $text;
        })
        ->addColumn('action', function ($Query) {
            $topics = "";
            if(isset($Query->topics) && !empty($Query->topics)){
                foreach ($Query->topics as $key => $value) {
                    $topics = implode(',',$Query->topics->pluck('topic_id')->toArray()); 
                }
            }

            $action_link = "";
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_resource openResourcePopoup' data-title = '".$Query->title."' data-short_description = '".$Query->short_description."' data-description = '".$Query->description."' data-src ='".$Query->src."' data-id = '".$Query->id."' data-is_url = '".$Query->is_url."' data-document = '".$Query->document."' data-ext = '".$Query->ext."' data-topic = '".$topics."' data-resource_order = '".$Query->resource_order."' ><i class='icon-pencil7 mr-3 text-primary edit_resource'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='resource_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status','src','topic_id'])
        ->make(true);
        return $data;
    }


    //Add Dance/Music Type
    public function store(Request $request){

        DB::beginTransaction();
        try{
            $status = 0;
            if(isset($request->status)){
                $status = 1;
            }
            $image = $request->file('src');
            $resourceData = [
                'title' => $request->title,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'status' => $status,
                "resource_order" => $request->resource_order
            ];

            $is_url = $resourceData['is_url'] = 0;
            if(isset($request->is_url) && $request->is_url == 1){
                $is_url = $resourceData['is_url'] = 1;
            }

            if($request->id){
                $resourceData['updated_by'] = Auth::id();
            } else {
                $resourceData['created_by'] = Auth::id();
            }

            $fileName = $request->id;
            if($request->hasFile('src')){
                $fileName = 'Img-' . time() . '.' . $image->getClientOriginalExtension();
                Helper::uploaddynamicFile(config('constant.resource_url'), $fileName,$image);
                $resourceData['src'] = $fileName;

                if(isset($request->old_src)){
                    Helper::checkFileExists(config('constant.resource_url') . $request->old_src, true, true);
                }
            }

            if(!$is_url){
                $extension = "";
                $document = $request->file('document');
                if($request->hasFile('document')){
                    $extension = $document->getClientOriginalExtension();
                    $fileName = 'Document-' . time() . '.' . $document->getClientOriginalExtension();
                    Helper::uploaddynamicFile(config('constant.resource_document_url'), $fileName,$document);
                    $resourceData['document'] = $fileName;
                    $resourceData['ext'] = $extension;

                    if(isset($request->old_document_src)){
                        Helper::checkFileExists(config('constant.resource_document_url') . $request->old_document_src, true, true);
                    }
                }
            }

            $resource = Resource::updateOrCreate(['id' => $request->id], $resourceData);
            if(isset($request->topic) && !empty($request->topic)){
                ResourceTopic::where('resource_id', $resource->id)->delete();
                foreach ($request->topic as $key => $topic) {
                    ResourceTopic::create(
                        [ 'resource_id' => $resource->id, 'topic_id' => $topic]
                    );
                }
            }

            DB::commit();

            if($request->id){
                return redirect()->route('resource.index')->with('success','Resource has been updated Successfully');
            }
            return redirect()->route('resource.index')->with('success','Resource has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $resource = Resource::where('id',$id)->first();
        return view('admin.resource.index',compact('resource'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $resource = Resource::find($id);
                $resource->delete();
                $resource->deleted_by = $user->id;
                $resource->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Resource has been deleted Successfully');
            } catch(Exception $e){
                DB::rollback();
                return array('status' => '0', 'msg_fail' => 'Something went wrong');
            }
        }
    }

    //Change Status
    public function changeStatus(Request $request){
        DB::beginTransaction();
        try {
            $resource = Resource::findOrFail($request->id);
            if ($resource->status == 0) {
                $resource->status = '1';
                $resource->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Resource has been activated successfully");
            } else {
                $resource->status = '0';
                $resource->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Resource has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
