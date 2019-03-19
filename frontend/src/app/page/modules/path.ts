import { AuthGuard } from '../../helper';
import * as nameComp from './';

import * as demo from '../../demo/index';

let session = JSON.parse(localStorage.getItem('user.data'));
export const pathMaster = [

    { canActivate: [AuthGuard], path: '', redirectTo: localStorage.getItem('user.data') != null ? 'dashboard-app' : 'login', pathMatch: 'full' },
    // { canActivate: [AuthGuard], path: 'dashboard', component: demo.DashboardDemoComponent },
    { canActivate: [AuthGuard], path: 'dashboard-app', component: nameComp.DashboardComponent },
    { canActivate: [AuthGuard], path: 'empty', component: demo.EmptyDemoComponent },
    // MASTER
    { canActivate: [AuthGuard], path: 'user-login', component: nameComp.UserLoginComponent },
    { canActivate: [AuthGuard], path: 'kelompok-user', component: nameComp.KelompokUserComponent },
    { canActivate: [AuthGuard], path: 'pegawai', component: nameComp.PegawaiComponent },
    { canActivate: [AuthGuard], path: 'produk', component: nameComp.ProdukComponent },
    { canActivate: [AuthGuard], path: 'alamat', component: nameComp.AlamatComponent },
    { canActivate: [AuthGuard], path: 'customer', component: nameComp.CustomerComponent },
    { canActivate: [AuthGuard], path: 'detail-jenis-produk', component: nameComp.DetailJenisProdukComponent },
    { canActivate: [AuthGuard], path: 'jenis-kelamin', component: nameComp.JenisKelaminComponent },
    { canActivate: [AuthGuard], path: 'jenis-produk', component: nameComp.JenisProdukComponent },
    { canActivate: [AuthGuard], path: 'jenis-transaksi', component: nameComp.JenisTransaksiComponent },
    { canActivate: [AuthGuard], path: 'kelompok-produk', component: nameComp.KelompokProdukComponent },
    { canActivate: [AuthGuard], path: 'satuan-standar', component: nameComp.SatuanStandarComponent },
    { canActivate: [AuthGuard], path: 'supplier', component: nameComp.SupplierComponent },
    { canActivate: [AuthGuard], path: 'toko', component: nameComp.TokoComponent },
    { canActivate: [AuthGuard], path: 'kode-generate', component: nameComp.KodeGenerateComponent },
    { canActivate: [AuthGuard], path: 'map-produk-to-satuan-standar', component: nameComp.MapProdukToSatuanStandarComponent },
    { canActivate: [AuthGuard], path: 'agama', component: nameComp.AgamaComponent },
    { canActivate: [AuthGuard], path: 'profile', component: pMaster.ProfileComponent },
    // END MASTER

    // TRANSAKSI
    { canActivate: [AuthGuard], path: 'penerimaan-barang-supplier', component: nameComp.PenerimaanBarangSupplierComponent },
    { canActivate: [AuthGuard], path: 'daftar-penerimaan-barang-supplier', component: nameComp.DaftarPenerimaanBarangSupplierComponent },
    { canActivate: [AuthGuard], path: 'transaksi-penjualan', component: nameComp.TransaksiPenjualanComponent },
    { canActivate: [AuthGuard], path: 'daftar-penjualan', component: nameComp.DaftarPenjualanComponent },
    { canActivate: [AuthGuard], path: 'stok-barang', component: nameComp.StokBarangComponent },
    { canActivate: [AuthGuard], path: 'penerimaan-barang-fix', component: nameComp.PenerimaanBarangFixComponent },
    { canActivate: [AuthGuard], path: 'retur-penjualan', component: nameComp.ReturPenjualanComponent },

    // END TRANSAKSI
    { canActivate: [AuthGuard], path: '404', component: nameComp.NotFoundComponent },
    { canActivate: [AuthGuard], path: '404', component: nameComp.NotFoundComponent },

    //APOTIK
 { canActivate: [AuthGuard], path: 'tes', component: pMaster.TesComponent },

    //END APPTIK

    { canActivate: [AuthGuard], path: '404', component: pMaster.NotFoundComponent },
    { canActivate: [AuthGuard], path: '**', redirectTo: '/404' },

];