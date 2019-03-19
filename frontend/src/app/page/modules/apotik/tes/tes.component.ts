import { Component, OnInit } from '@angular/core';
import { HttpClient } from '../../../../helper';
import { FormGroup, FormBuilder, FormControl } from '@angular/forms';

@Component({
  selector: 'app-tes',
  templateUrl: './tes.component.html',
  styleUrls: ['./tes.component.scss']
})
export class TesComponent implements OnInit {

  formGroup: FormGroup;
  dataSource: any[]
  
  constructor(
    private httpService : HttpClient,
    private fb: FormBuilder
  ) { }

  ngOnInit() {

    this.formGroup = this.fb.group({
      'nik': new FormControl(null),
      'nama': new FormControl(null),
    });

    this.cari()
  }

  cari(){
    this.httpService.get('tes/get-daftar-tes?nama=' 
    + this.formGroup.get('nama').value
    + '&nik=' + this.formGroup.get('nik').value
    ).subscribe(e=>{
      debugger;
      this.dataSource = e.data
    })
  }

}
