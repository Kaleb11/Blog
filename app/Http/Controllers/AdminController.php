<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Blog;
use App\Models\Role;
use App\Models\User;
use App\Models\Blogtag;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Blogcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends Controller
{
    public function index(Request $request){

        // First check if you are loggedin and admin user
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() == 'login'){
            return view('welcome');
        }
       
       // You are already logged in... so check for if you are an admin user
            $user = Auth::user();
            if ($user->userType == 'User'){
                return redirect('/login');
            }
            if($request->path() == 'login'){
                return redirect('/dashboard');
                //return redirect('/login');
            }
        return $this->checkForPermission($user,$request);
        // return view('notfound');
         //return view('welcome');
        
         
    }
    public function checkForPermission($user, $request){
     $permission = json_decode($user->role->permission);
     $hasPermission = false;
     foreach($permission as $p){
         if($p->name == $request->path()){
              if($p->read){
                  $hasPermission = true;
              
         }
        
     }
     if($p->name == $request->path()){
        if(!$p->read){
            $hasPermission = false;
            return view('notfound');
        
   }}
    if(Str::contains($request->path(), 'editblog')){
        if($p->name == 'blogs'){
            if($p->update){
                $hasPermission = true;
            
       }
      
   }
    }
    }
    
     if($hasPermission) return view('welcome');
     return redirect('/dashboard');
     //return view('notfound');
     echo $permission[0]->name;
     echo $request->path();
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
    public function addTag(Request $request){
        // validate
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function addCategory(Request $request){
        // validate
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
    public function getTag(Request $request){
        return Tag::orderBy('id','desc')->paginate($request->total);
    }
    public function getCategory(Request $request){
        return Category::orderBy('id','desc')->paginate($request->total);
    }
    public function updateTag(Request $request){
        // validate
        $this->validate($request, [
            'tagName' => 'required',
            'id'=> 'required'
        ]);
        return Tag::where('id',$request->id)->update([
            'tagName' => $request -> tagName
        ]);
    }
    public function updateCategory(Request $request){
        // validate
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id'=> 'required'
        ]);
        return Category::where('id',$request->id)->update([
            'categoryName' => $request -> categoryName,
            'iconImage' => $request -> iconImage
        ]);
    }
    public function deleteTag(Request $request){
        $this->validate($request, [
            'id'=> 'required'
        ]);
        return Tag::where('id',$request->id)->delete();
    }
    public function deleteCategory(Request $request){  
        // First delete the orginal file from server
       
        // $filePath = $request -> iconImage;
       
        $this->deleteFileFromServer($request -> iconImage);
        $this->validate($request, [
            'id'=> 'required'
        ]);
        return Category::where('id',$request->id)->delete();
    }
    public function upload(Request $request){
        $routePath = $request->path();
        $this->validate($request, [
            'file'=> 'required|mimes:jpeg,jpg,png'
        ]);
        $picName =time().'.'.$request->file->extension();
        if($routePath=='app/upload/user/photo'){
            $request->file->move(public_path('uploads/User photos'),$picName);
        }else if($routePath=='app/upload/blog/feature/photo'){
            $request->file->move(public_path('uploads/BlogFeatureImage'),$picName);
        }else{
            $request->file->move(public_path('uploads'),$picName);
        }
        
        //$path=public_path()."/uploads/".$picName;
        return $picName;
    }
    public function deleteImage(Request $request){
        if($request->photo){
          $fileName = $request->photo;
          $fileName = 'User photos/'.$fileName;
          $this->deleteFileFromServer($fileName, false);
       }else if($request->featuredImage){
        $fileName = $request->featuredImage;
        $fileName = 'BlogFeaturePhoto/'.$fileName;
        $this->deleteFileFromServer($fileName, false);
       }
       
       else{
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName, false);
    }
        
        return 'Done';
    }
    public function deleteFileFromServer($fileName,$hasFullPath = false){
        if(!$hasFullPath){
            $filePath = public_path().'/uploads/'.$fileName;
            
        }
        
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }
    public function createUser(Request $request){
        // validate
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'photo' => 'required',
            'role_id' => 'required'
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' =>$password,
            'photo' => $request->photo,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }
    public function getUsers(Request $request){
        return User::Where('role_id','!=','4')->orderBy('id','desc')->paginate($request->total);
        
    }
   
    public function searchUser(Request $request){
        $str = $request->str;
        $users = User::orderBy('id','desc')->select('id','fullName','email','photo',
        'role_id','created_at');
      // $users= $users->where(DB::raw('lower(fullName)'), "LIKE", "%".strtolower($str)."%");

        //    ->orwhere(DB::raw(str::lower(strstr("email","@",true))), "LIKE", "%".strtolower($str)."%");
          
        $users->when($str != '', function($q) use($str){
            $q->where('fullName', "ilike", "%".$str."%")
           ->orWhere('email', "ilike", "%".$str."%")
           ->orWhere('created_at', "ilike", "%".$str."%")
           ->orwhereHas('role',function($q) use($str){
            $q->where('roleName', "ilike", "%".($str)."%"); 
         });
         });
         
        
         $users = $users->paginate(5);
        
        
        return $users;
       
     }
    
    public function updateUser(Request $request){
        // validate
        $this->validate($request, [
            'fullName' => 'required',
            'password' => "min:6",
            'email' => "bail|required|email|unique:users,email,$request->id",
            'photo' => 'required',
            'role_id' => 'required',
            'id'=> 'required'
        ]);
        $data = [
            'fullName' => $request -> fullName,
            'email' => $request -> email,
            'photo' => $request -> photo,
            'role_id' => $request -> role_id,
        ];
        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        return User::where('id',$request->id)->update($data);
    }
    public function deleteUser(Request $request){
        $this->validate($request, [
            'id'=> 'required'
        ]);
        return User::where('id',$request->id)->delete();
    }
    
    public function adminLogin(Request $request){
        $this->validate($request, [
            'email' => "bail|required|email",
            'password' => "bail|required|min:6",
            
    
        ]);
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            //return $user->role->roleName;
            // return response()->json([
            //     'msg' => $user->role(),
            
               
            // ],200);
            if($user->role->isAdmin == false){
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                ],401);
            } 
            return response()->json([
                 'msg' => 'You are logged in',
                 'user' => $user
             ],200);
        }else{
            
            return response()->json([
                'msg' => 'Incorrect login details',
            ],401);
        }
      
        
        }
    public function addRole(Request $request){
            // validate
            $this->validate($request, [
                'roleName' => 'required',
                'isAdmin' => 'required'
            ]);
            return Role::create([
                'roleName' => $request->roleName,
                'isAdmin' => $request->isAdmin
            ]);
        }
    public function updateRole(Request $request){
            // validate
            $this->validate($request, [
                'isAdmin' => 'required',
                'roleName' => 'required',
                'id'=> 'required'
            ]);
            return Role::where('id',$request->id)->update([
                'roleName' => $request -> roleName,
                'isAdmin' => $request -> isAdmin
            ]);
        }
    public function deleteRole(Request $request){
            $this->validate($request, [
                'id'=> 'required'
            ]);
            return Role::where('id',$request->id)->delete();
        }
    public function getRole(Request $request){
        return Role::orderBy('id','desc')->paginate($request->total);
    }
    public function assignRoles(Request $request){
        // validate
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id',$request->id)->update([
            'permission' => $request->permission
        ]);
    }
    public function uploadEditorImage(Request $request){
        $this->validate($request, [
            'image'=> 'required|mimes:jpeg,jpg,png'
        ]);
        $picName =time().'.'.$request->image->extension();
        
        $request->image->move(public_path('uploads/BlogImages'),$picName);
        return response()->json([
            'success' =>1,
            "file"=> [
                "url" => "http://127.0.0.1:8000/uploads/BlogImages/$picName"
                // ... and any additional fields you want to store, such as width, height, color, extension, etc
            ]
        ]);
        
        //$path=public_path()."/uploads/".$picName;
        return $picName;
    }
    public function slug(){
      $title = 'This is the title';
      return Blog::create([
          'title' => $title,
          'post'=> 'Some post',
          'post_excerpt' => 'asda',
          'user_id'=>3,
          'metaDescription'=>'asda',
          'featuredImage'=>'uiou'
      ]);
      return $title;
    }
    public function createBlog(Request $request){
    $this->validate($request, [
            'title'=> 'required',
            'post'=> 'required',
            'post_excerpt'=> 'required',
            'metaDescription'=> 'required',
            'featuredImage'=>'required'
        ]);
    $categories = $request->category_id;
    $tags = $request->tag_id;

    $blogCategories = [];
    $blogTags = [];

    DB::beginTransaction();
    try{
        $blog= Blog::create([
            'title' =>$request-> title,
            'slug' => $request->title,
            'post'=>$request-> post,
            'post_excerpt' => $request-> post_excerpt,
            'user_id'=>Auth::user()->id,
            'metaDescription'=> $request-> metaDescription,
            'jsonData'=>$request->jsonData,
            'featuredImage'=>$request->featuredImage
        ]);
        // $blogCategories = [];
        // foreach($categories as $c){
        //     array_push($blogCategories,['category_id'=> $c, 'blog_id'=>$blog->id]);
        // }
        // Blogcategory::insert[$blogCategories];
        // foreach($categories as $c){
        //     Blogcategory::create([
        //         'category_id' => $c,
        //         'blog_id' => $blog ->id
        //     ]);
        // }
        
        foreach($categories as $c){
            array_push($blogCategories,['category_id'=> $c, 'blog_id'=>$blog->id]);
        }
        Blogcategory::insert($blogCategories);

        
        foreach($tags as $t){
            array_push($blogTags,['tag_id'=> $t, 'blog_id'=>$blog->id]);
        }
        Blogtag::insert($blogTags);
        DB::commit();
        return 'Done';
    }catch(\Throwable $th){
        DB::rollBack();
        return Response::json(array(
            'code'      =>  404,
            'message'   =>  "Not done",
            'error'     =>  $th
        ), 404); 
    }
        
        
      }
public function blogData(Request $request){
    

    return Blog::with(['tag','cat'])->orderBy('id','desc')->paginate($request->total);
}
public function blogItem(Request $request,$id){
    return Blog::with(['tag','cat'])->where('id',$id)->first();
}
public function updateBlog(Request $request,$id){
    $this->validate($request, [
        'title'=> 'required',
        'post'=> 'required',
        'post_excerpt'=> 'required',
        'metaDescription'=> 'required',
        'featuredImage'=> 'required'
    ]);
    $categories = $request->category_id;
    $tags = $request->tag_id;

    $blogCategories = [];
    $blogTags = [];

    DB::beginTransaction();
    try{
        Blog::where('id',$id)->update([
            'title' =>$request-> title,
            'slug' => $request->title,
            'post'=>$request-> post,
            'post_excerpt' => $request-> post_excerpt,
            'user_id'=>Auth::user()->id,
            'metaDescription'=> $request-> metaDescription,
            'jsonData'=>$request->jsonData,
            'featuredImage'=>$request->featuredImage
        ]);
        
        foreach($categories as $c){
            array_push($blogCategories,['category_id'=> $c, 'blog_id'=>$id]);
        }
        //Delete all previous categories and tags
        Blogcategory::where('blog_id',$id)->delete();
        Blogcategory::insert($blogCategories);

        
        foreach($tags as $t){
            array_push($blogTags,['tag_id'=> $t, 'blog_id'=>$id]);
        }
        Blogtag::where('blog_id',$id)->delete();
        Blogtag::insert($blogTags);
        DB::commit();
        return 'Done';
    }catch(\Throwable $th){
        DB::rollBack();
        return Response::json(array(
            'code'      =>  404,
            'message'   =>  "Not done"
        ), 404); 
    }
}
public function deleteBlog(Request $request){
    $this->validate($request, [
        'id'=> 'required'
    ]);
    // Blogcategory::where('blog_id',$request->id)->delete();
    //BlogTag::where('blog_id',$request->id)->delete();
    return Blog::where('id',$request->id)->delete();
}

}
