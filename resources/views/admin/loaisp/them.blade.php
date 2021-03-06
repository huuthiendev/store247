@extends('admin.layout.index')    

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Loại sản phẩm
                    <small>Thêm mới</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
            <div class="col-lg-7" style="padding-bottom:120px">                
                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach($errors -> all() as $err)
                            {{$err}}<br/>
                        @endforeach
                    </div>
                @endif

                @if(session('thongbao'))
                    <div class="alert alert-success">
                        {{session('thongbao')}}
                    </div>
                @endif

                @if(session('loi'))
                    <div class="alert alert-warning">
                        {{session('loi')}}
                    </div>
                @endif
                <button type="button" class="btn btn-default btn-sm" onclick="location.href='admin/loaisp/danhsach';">
                    <span class="fa fa-chevron-left"></span> Trở về
                </button>
                <form action="admin/loaisp/them" method="POST" enctype="multipart/form-data">
                    <br/>
                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                    <div class="form-group">
                        <label>Tên loại sản phẩm</label>
                        <input class="form-control" name="ten" placeholder="Vui lòng điền tên loại sản phẩm" />
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="themNhomLoaiSP">
                        <label>Chọn nhóm loại sản phẩm</label>

                        <select class="form-control nhomloaisp" name="maloaicha" disabled="">
                            @foreach($dsloaisp as $loaisp)
                                <option value="{{$loaisp -> id}}">{{$loaisp -> tenloaisp}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Hình loại sản phẩm</label>
                        <input type="file" class="form-control" name="hinh" />
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                    <button type="reset" class="btn btn-default">Làm mới</button>
                <form>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#themNhomLoaiSP').change(function(){
                if($(this).is(':checked'))
                {
                    $('.nhomloaisp').removeAttr('disabled');
                }
                else 
                {
                    $('.nhomloaisp').attr('disabled','');
                }
            });
        });
    </script>
@endsection