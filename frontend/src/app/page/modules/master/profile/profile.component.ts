import { Component, OnInit } from '@angular/core';
import { HttpClient, AlertService, InfoService, LoaderService } from '../../../../helper';
import { FormGroup, FormBuilder, FormControl } from '@angular/forms';
import { ConfirmationService } from 'primeng/primeng';
import { DataHandler } from '../../../../helper/handler/DataHandler';
import { MessageService } from 'primeng/components/common/messageservice';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  displayDialog: boolean = false
  image: any =  []
  imagepreview: any[] = []
  formGroup: FormGroup;
  dataSource: any;
  uploadedFiles: any[] = [];
  constructor(private alertService: AlertService,
    private InfoService: InfoService,
    private httpService: HttpClient,
    // private confirmationService: ConfirmationService,
    private dataHandler: DataHandler,
    private fb: FormBuilder,
    private loader: LoaderService
  ) { }

  ngOnInit() {
    this.formGroup = this.fb.group({
      'namaapotek': new FormControl(null),
      'pemilik': new FormControl(null),
      'alamat': new FormControl(null),
      'kotakabupaten': new FormControl(null),
      'provinsi': new FormControl(null),
      'telepon': new FormControl(null),
      'email': new FormControl(null),
      'kodepos': new FormControl(null),
      'npwp': new FormControl(null),
      'metodestok': new FormControl(null),
      'metodeharga': new FormControl(null),
      'pesan': new FormControl(null),
    });
    this.image.push({source:'assets/img/bg-log.png', alt:'Apotek Kintil', title:'Profil'});
  }

  showDialogToAdd(){
    this.displayDialog=true
  }
  onUpload(event) {
    debugger;
    for(let file of event.files) {
        this.uploadedFiles.push(file);
        this.imagepreview.push({source:'assets/img/bg-log.png', alt:'Apotek Kintil', title:'Profil'});
    }

    this.alertService.info('tes','as')
}
}
