<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\SanPham;
use App\ThuongHieu;
use App\LoaiSanPham;
use App\HoaDon;
use App\ChiTietHoaDon;
use Gloudemans\Shoppingcart\Facades\Cart;

class GioHangController extends Controller
{
    public function Them($id)
    {
    	$sanpham = SanPham::find($id);
        Cart::add($id ,$sanpham->tensp, 1, $sanpham->gia, ['image' => $sanpham->hinhsanpham->first()->duongdan]);
    	return redirect()->route('giohang');
    }

    public function DanhSach()
    {
        $dsloaisp = LoaiSanPham::all();
        $dsthuonghieu = ThuongHieu::all();
        return view('store.giohang',['giohang' => Cart::content(), 'tong' => Cart::subtotal(), 'tongsl' => Cart::count(), 'dsloaisp' => $dsloaisp, 'dsthuonghieu' => $dsthuonghieu]);
    }

    public function Xoa($id)
    {
        $giohang = Cart::content();
        foreach ($giohang as $item) {
            if($item -> id == $id) {
                Cart::remove($item->rowId);
                return redirect()->route('giohang');
            }
        }
    }

    public function CapNhatSoLuong($loai, $id)
    {
        if ($loai == 'cong') 
        {
            $giohang = Cart::content();
            foreach ($giohang as $item) {
                if($item -> id == $id) {
                    Cart::update($item->rowId, $item->qty + 1);
                    $tongtien = Cart::subtotal();
                    $tonggia = $item -> price * $item -> qty;
                    return redirect()->route('giohang');
                }
            }
        }
        if ($loai == 'tru')
        {
            $giohang = Cart::content();
            foreach ($giohang as $item) {
                if($item -> id == $id) {
                    Cart::update($item->rowId, $item->qty - 1);
                    $tongtien = Cart::subtotal();
                    $tonggia = $item -> price * $item -> qty;
                    return redirect()->route('giohang');
                }
            }
        }
        
    }

    public function LamMoi()
    {
        Cart::destroy();
        return redirect("");
    }

    public function ThanhToan()
    {
        $dsloaisp = LoaiSanPham::all();
        $dsthuonghieu = ThuongHieu::all();
        return view('store.thanhtoan', ['giohang' => Cart::content(), 'tong' => Cart::subtotal(), 'dsloaisp' => $dsloaisp, 'dsthuonghieu' => $dsthuonghieu]);
    }

    public function LuuThanhToan(Request $request)
    {
        $this->validate($request, 
            ['hoten'=>'min:3',
             'diachi'=>'min:5'], 
            ['hoten.min'=>'Họ tên phải có độ dài tối thiểu 3 ký tự',
             'diachi.min'=>'Địa chỉ phải có độ dài tối thiểu 5 ký tự']);
        $hoadon = new HoaDon;
        $hoadon->mand = Auth::user()->id;
        $hoadon->sdt = $request->sdt;
        $hoadon->diachi = $request->diachi;
        $hoadon->trangthai = 0;
        $tong = 0;
        $giohang = Cart::content();
        foreach ($giohang as $item) {
            $tong = $item->price * $item->qty + $tong;
        }
        $hoadon->tongtien = $tong;
        $hoadon->ghichu = $request->ghichu;
        $hoadon->save();
        
        foreach ($giohang as $item) {
            $cthoadon = new ChiTietHoaDon;
            $cthoadon->mahd = $hoadon->id;
            $cthoadon->masp = $item->id;
            $cthoadon->soluong = $item->qty;
            $cthoadon->save();
        }
        Cart::destroy();
        return redirect()->route('thanhcong');
    }

    public function ThanhCong()
    {
        $dsloaisp = LoaiSanPham::all();
        $dsthuonghieu = ThuongHieu::all();
        return view('store.thanhcong',['dsloaisp' => $dsloaisp, 'dsthuonghieu' => $dsthuonghieu]);
    }
}
