import { Component, OnInit } from '@angular/core';
import { HttpClient } from '../../../../helper/service/HttpClient';
import { DataHandler } from '../../../../helper/handler/DataHandler';
import { TableHandler } from '../../../../helper/handler/TableHandler';
import { Observable } from 'rxjs/Rx';
import { LazyLoadEvent, Message, ConfirmDialogModule, ConfirmationService, SelectItem } from 'primeng/primeng';
import { AlertService, InfoService, Configuration, LoaderService } from '../../../../helper';
import { FormBuilder, FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';

@Component({
  selector: 'app-pegawai',
  templateUrl: './pegawai.component.html',
  styleUrls: ['./pegawai.component.scss'],
  providers: [ConfirmationService]
})
export class PegawaiComponent implements OnInit {

  formGroup: FormGroup;
  displayDialog: boolean;
  dataSource: any;
  now: any = new Date;
  listJK: SelectItem[];
  listAgama: SelectItem[];
  constructor(private alertService: AlertService,
    private InfoService: InfoService,
    private httpService: HttpClient,
    private confirmationService: ConfirmationService,
    private dataHandler: DataHandler,
    private fb: FormBuilder,
    private loader: LoaderService
  ) { }


  ngOnInit() {
    this.getCombo()
    this.formGroup = this.fb.group({
      'KdPegawai': new FormControl(null),
      'NamaPegawai': new FormControl(null),
      'KdAgama': new FormControl(null),
      'NoTelpon': new FormControl(null),
      'Alamat': new FormControl(null),
      'KdJenisKelamin': new FormControl(null),
      'NIK': new FormControl(null),
      'TglLahir': new FormControl(this.now),
    });

    this.getData()
  }

  showDialogToAdd() {

    this.resetForm()
    this.displayDialog = true;
  }
  getCombo() {
    this.httpService.get('master/get-combo').subscribe(data => {
      var getData: any = this.dataHandler.get(data);
      this.listJK = [];
      this.listJK.push({ label: '--Pilih--', value: '' });
      getData.jeniskelamin.forEach(response => {
        this.listJK.push({ label: response.JenisKelamin, value: response.KdJenisKelamin });
      });

      this.listAgama = [];
      this.listAgama.push({ label: '--Pilih--', value: '' });
      getData.agama.forEach(response => {
        this.listAgama.push({ label: response.Agama, value: response.KdAgama });
      });
    }, error => {
      // this.alertService.error('Error', JSON.stringify(error));
    });
  }
  getData() {
    this.httpService.get('master/pegawai/get-daftar-pegawai').subscribe(data => {
      if( data.data.length > 0){
        for (let i = 0; i <  data.data.length; i++) {
          const element =  data.data[i];
          element.TglLahir = moment(new Date(element.TglLahir)).format('DD-MM-YYYY')
          
        }
      }
      this.dataSource = data.data
    })

  }
  save() {

    let json = {
      'KdPegawai': this.formGroup.get('KdPegawai').value,
      'KdAgama': this.formGroup.get('KdAgama').value,
      'NamaPegawai': this.formGroup.get('NamaPegawai').value,
      'NoTelpon': this.formGroup.get('NoTelpon').value,
      'NIK': this.formGroup.get('NIK').value,
      'KdJenisKelamin': this.formGroup.get('KdJenisKelamin').value,
      'Alamat': this.formGroup.get('Alamat').value,
      'TglLahir': moment(this.formGroup.get('TglLahir').value).format('YYYY-MM-DD')
    }

    this.httpService.post('master/pegawai/save-pegawai', json).subscribe(data => {
      this.getData()
      this.resetForm()
    }, error => {
      // this.alertService.error('Error', JSON.stringify(error));
    });

  }
  edit(e) {
    this.formGroup.get('KdPegawai').setValue(e.KdPegawai);
    this.formGroup.get('NamaPegawai').setValue(e.NamaPegawai);
    this.formGroup.get('KdAgama').setValue(e.KdAgama);
    this.formGroup.get('NoTelpon').setValue(e.NoTelpon);
    this.formGroup.get('NIK').setValue(e.NIK);
    this.formGroup.get('KdJenisKelamin').setValue(e.KdJenisKelamin);
    this.formGroup.get('Alamat').setValue(e.Alamat);
    this.formGroup.get('TglLahir').setValue(new Date(e.TglLahir));
    this.displayDialog = true;
  }
  hapus(e) {

    let jsonDelete = {
      'KdPegawai': e.KdPegawai
    }
    this.confirmationService.confirm({
      message: 'Yakin mau menghapus data?',
      accept: () => {
        this.httpService.post('master/pegawai/delete-pegawai', jsonDelete).subscribe(data => {
          this.getData()
          this.resetForm()
        }, error => {
          // this.alertService.error('Error', JSON.stringify(error));
        });
      }
    })
  }
  resetForm() {
    this.formGroup.get('KdPegawai').reset()
    this.formGroup.get('NamaPegawai').reset()
    this.formGroup.get('KdAgama').reset()
    this.formGroup.get('NoTelpon').reset()
    this.formGroup.get('NIK').reset()
    this.formGroup.get('KdJenisKelamin').reset()
    this.formGroup.get('Alamat').reset()


  }
}
