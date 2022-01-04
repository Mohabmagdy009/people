@extends('layouts.app')

@section('style')
<!-- CSS -->
<link href="{{ asset('/plugins/gentelella/build/css/custom2.css') }}" rel="stylesheet">
@stop

@section('scriptsrc')
<!-- All JS and JQ scripts needed -->
@stop

@section('content')
<div class="page-title"></div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
         <div class="x_content"> 
            <div class="container">
               <h3 class="text-center">Upload Your Excel File</h3>
               <form action="{{'uploadfile'}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="custom-file">
                     <span id="uploadText">Upload Your File</span>
                     <input type="file" name="file" class="custom-file-input" id="chooseFile">
                     <div>
                        <button type="submit" name="submit" class="btn btn-primary btn-block mt-4" style="width: 10%;">Upload Files</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
         <div class="x_content"> 
            <div class="container">
               <h3 class="text-left">Loader FeedBack</h3>
               <div id="FeedBack" style="color:{{$color}}">{{$feedBack}}</div> <!-- Values we got from the ToolsController -->
               <ul class="points" style="display:none;">
                 <li>File extension must be .xlsx</li>
                 <li>It should have 2 headers (name and type)</li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('script')
<script>
   $(document).ready(function(){
      $(".points").css("display","none"); 
      var message = $("#FeedBack").html();
      if(message==""){
         $("#FeedBack").css("display","none");
      }
      else if(message == "The loader could not upload your file"){
         $("#FeedBack").css("display","block");
         $(".points").css("display","block");
      }
      else{
         $("#FeedBack").css("display","block");
      }
   })
</script>
@stop
