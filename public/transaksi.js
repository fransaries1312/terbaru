$(document).ready(function() {        
    count_table();
    calculate();

    $('#obat').typeahead({
        source: function (query, process) {
            var $items = new Array;
            $items = [""];
            $.ajax({
                url: "modules/transaksi/aksi_transaksi.php?module=transaksi&act=query",
                data: 'query=' + query,            
                dataType: "json",
                type: "POST",
                success: function (data) {
                    // console.log(data);
                    $.each(data,function(index, el) {
                        // $.map(el, function (item) {
                            var group;
                            group = {
                                id: el.id,
                                name: el.name,                            
                                toString: function () {
                                    return JSON.stringify(this);
                            //return this.app;
                        },
                        toLowerCase: function () {
                            return this.name.toLowerCase();
                        },
                        indexOf: function (string) {
                            return String.prototype.indexOf.apply(this.name, arguments);
                        },
                        replace: function (string) {
                            var value = '';
                            value +=  this.name;
                            if(typeof(this.level) != 'undefined') {
                                value += ' <span class="pull-right muted">';
                                value += this.level;
                                value += '</span>';
                            }
                            return String.prototype.replace.apply('<div style="padding: 10px; font-size: 1.5em;">' + value + '</div>', arguments);
                        }
                    };

                    $items.push(group);
                // })
                        process($items);
                    });
                    
                }
            });
        },
        property: 'obat',
        items: 10,
        minLength: 2,
        updater: function (item) {
            var item = JSON.parse(item);
            var promise = cek_stok(item.id);
            if(promise==true)
            {
                $('#id_obat').val(item.id);     
                $('#nama_barang').val(item.name);        
            }
            return item.name;
        }
    });

    var input = $('#obat');
    input.on('keydown', function() {
        var key = event.keyCode || event.charCode;

        if( key == 8 || key == 46 )
            $('#id_obat').val('');       
    });          

    $('.count').bind('keyup change',function(){
        if($('#id_obat').val()=='')
        {
            toastr.warning('Silahkan pilih obat terlebih dahulu!','Peringatan');
            $('#qty').val(0);
            $('#diskon').val(0);
            $('#subtotal').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#subtotal1').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#total').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#total1').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#nama_barang').val('');        
        }
        else
        {
            count();
        }
    })   


    $('#add_cart').on('click',function(){
        if($('#id_obat').val()=='')
        {
            toastr.warning('Silahkan pilih obat terlebih dahulu!','Peringatan');
            $('#qty').val(0);
            $('#diskon').val(0);
            $('#subtotal').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#subtotal1').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#total').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#total1').val(numberToCurrency2(Math.floor(pecahan(0))));
            $('#nama_barang').val('');        
            return false;
        }
        else
        {
            if($('#qty').val()==0)
            {
                toastr.warning('Masukkan qty penjualan!','Peringatan');
                return false
            }
            else
            {
                var id_detail;
                id_detail=transaksi_belum_selesai();
                // console.log(id_detail);
                var last_count=$('#count_table').val();
                add_table($('#count_table').val());
                $('#barang_'+last_count).html($('#nama_barang').val());
                $('#harga_'+last_count).html('Rp '+$('#harga').val());
                $('#qty_'+last_count).html($('#qty').val());
                $('#total_'+last_count).html('Rp '+$('#subtotal').val());
                $('#diskon_'+last_count).html($('#diskon').val());
                $('#subtotal_'+last_count).html('Rp '+$('#total').val());
                $('#subtotal1_'+last_count).val($('#total1').val());
                $('#delete_db_'+last_count).val(id_detail);

                reset();
                calculate();
  
            }
            
        }
    })  

    $( "#list" ).on( "click", ".delete_barang_data_detail", function() {
        var no = $(this).attr('id');
        //var stat ='edit';
        delete_data_obat_table(no);
        return false;
    });
      

      $('#bayar').bind('keyup change',function(){
        if($('#bayar').val()=='')
        {
            $('#bayar').val(0);
            $('#kembali').val(0);
        }
        hitung();
      })

      $('.number_dot').on('change keyup',function(){
        nominal = currencyToNumber($(this).val());
        if(isNaN(nominal)){
            $(this).val(0);    
        } else {
            $(this).val(addCommas(nominal));
        }
        //alert(nominal);
    })

      $('#form1').submit('#simpan',function(e){
        e.preventDefault();
        var count_err=0;
        if($('#tabel tbody tr').length==0)
        {
            toastr.warning('Silahkan anda masukkan obat!','Peringatan');
            count_err+=1;
        }
        else
        {
            count_err-=1;
            if(count_err<0)
            {
                count_err=0;
            }
        }

        if($('#bayar').val()==0)
        {
            toastr.warning('Silahkan masukkan nominal uang!','Peringatan');
            count_err+=1;
        }
        else
        {
            count_err-=1;
            if(count_err<0)
            {
                count_err=0;
            }
        }


        if(parseFloat(currencyToNumber($('#bayar').val()))-parseFloat($('#subtotal_view1').val())<0)
        {
            console.log('aaaa');
            toastr.warning('Silahkan masukkan nominal uang!','Peringatan');
            count_err+=1;
        }
        else
        {
            count_err-=1;
            if(count_err<0)
            {
                count_err=0;
            }
        }

        if (count_err==0) {
            $('#form1')[0].submit();
            return true;
        }
        else
        {
            return false;
        }

      })

      $('#cancel').on('click',function(){
        batal_transaksi($('#nota').val());
      })

} );



function delete_data_obat_table(no){
    console.log(no);
    if(confirm("Anda yakin akan menghapus data ini?")){
      if($('.isi_tabel').length < 1){

        $('#hide_count').val(1);
    }

    if($('#delete_db_'+no).val()!==undefined)
    {
        delete_data($('#delete_db_'+no).val());
    }

    $('#data_ke_'+no).detach();
    count_table();
    calculate();
    hitung();
    return false;
}
}      


function cek_stok(id)
        {
            var status;
            $.ajax({
                url : "modules/transaksi/aksi_transaksi.php?module=transaksi&act=cek_stok_barang",
                type: "GET",
                dataType: "JSON",
                async: false,
                data:{id:id},
                success: function(data){
                    // console.log(data);
                    if(data.status==true)
                    {
                        $('#stok').val(data.stok);
                        $('#stok1').val(data.stok);
                        $('#harga').val(numberToCurrency2(Math.floor(pecahan(data.harga_obat))));
                        status=true;
                    }
                    else
                    {
                        toastr.warning(data.msg,'Peringatan');
                        $('#stok').val(data.stok);
                        $('#stok1').val(data.stok);
                        $('#id_obat').val('');   
                        $('#qty').val(0);  
                        $('#diskon').val(0);  
                        $('#harga').val(numberToCurrency2(Math.floor(pecahan(0))));
                        $('#subtotal').val(numberToCurrency2(Math.floor(pecahan(0))));
                        $('#subtotal1').val(numberToCurrency2(Math.floor(pecahan(0))));
                        $('#total').val(numberToCurrency2(Math.floor(pecahan(0))));
                        $('#total1').val(numberToCurrency2(Math.floor(pecahan(0))));
                        $('#nama_barang').val('');        
                        status=false;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                  alert('Error get data from ajax');
              }
          });

            return status
      }

      function count()
      {
        console.log();
            var stok=parseInt($('#stok1').val());
            var qty=parseInt($('#qty').val());
            var diskon=parseFloat($('#diskon').val());

            var subtotal=currencyToNumber($('#harga').val());

            var result=stok-qty;

            if(result>0)
            {
                $('#subtotal').val(numberToCurrency2(Math.floor(pecahan(subtotal * qty))))
                $('#subtotal1').val(Math.floor(pecahan(subtotal * qty)))
                $('#stok').val(result);
                $('#total').val(numberToCurrency2(Math.floor((subtotal * qty)-((diskon/100)*(subtotal * qty)))));
                $('#total1').val(Math.floor((subtotal * qty)-((diskon/100)*(subtotal * qty))));
                // console.log(Math.floor((subtotal * qty)-((diskon/100)*(subtotal * qty))));
            }
            else
            {
                toastr.warning('Qty yang anda masukkan melebihi persediaan stok yang ada!','Peringatan');
                $('#qty').val(0);
                $('#diskon').val(0);
                $('#subtotal').val(numberToCurrency2(Math.floor(pecahan(0))));
                $('#subtotal1').val(numberToCurrency2(Math.floor(pecahan(0))));
                $('#total').val(numberToCurrency2(Math.floor(pecahan(0))));
                $('#total1').val(numberToCurrency2(Math.floor(pecahan(0))));
                $('#nama_barang').val('');        
            }
     }

     function currencyToNumber(a){
        if(a!=null||a!=''){
            var b=a.toString();
            var pecah_koma = b.split(',');
            pecah_koma[0]=pecah_koma[0].replace(/\.+/g, '');
            c=pecah_koma.join('.');
            return parseFloat(c);
        }else{
            return '';
        }
    }

    function numberToCurrency2(a){
        if(a!=null&&!isNaN(a)){
        //var b=Math.ceil(parseFloat(a));
        var b=parseInt(a);
        var angka=b.toString();
        var c = '';
        var lengthchar = angka.length;
        var j = 0;
        for (var i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = angka.substr(i-1,1) + '.' + c;
                } else {
                        c = angka.substr(i-1,1) + c;
                }
            }
            return c;
        }else{
            return '';
        }
    }

     function pecahan(uang)
     {
        pembulatan=Math.round(uang / 100) * 100;
        return pembulatan;
    }

    function add_table(count)
    {
         $('<tr class="isi_tabel" id="data_ke_'+count+'">\n\
            <td>\n\
            <a href="#" id="'+count+'" class="btn btn-xs btn-danger delete_barang_data_detail">Hapus</a>\n\
            <input type="hidden" id="delete_db_'+count+'" name="id_detail_rekap[]" value="">\n\
            </td>\n\
            <td align="left">\n\
            <span id="barang_'+count+'"></span>\n\
            </td>\n\
            <td align="right">\n\
            <span id="harga_'+count+'"></span>\n\
            </td>\n\
            <td align="right">\n\
            <span id="qty_'+count+'"></span>\n\
            </td>\n\
            <td align="right">\n\
            <span id="total_'+count+'"></span>\n\
            </td>\n\
            <td align="right">\n\
            <span id="diskon_'+count+'"></span>\n\
            </td>\n\
            <td align="right">\n\
            <span id="subtotal_'+count+'"></span>\n\
            <input type="hidden" class="count_sub" id="subtotal1_'+count+'">\n\
            </td>\n\
            </tr>').appendTo('#list');

         count_table();
    }

    function count_table()
    {
        var count=$('#tabel tbody tr').length;
        $('#count_table').val(count);

        $('.isi_tabel').each(function(index, el){
            // console.log(el);
            if($('#tabel tbody tr').length>0)
            {
               
               $(this).attr("id",index);
               $(this).attr("id","delete_db_"+ (index));
               $(this).attr("id","barang_"+ (index));
               $(this).attr("id","harga_"+ (index));
               $(this).attr("id","qty_"+ (index));
               $(this).attr("id","total_"+ (index));
               $(this).attr("id","diskon_"+ (index));
               $(this).attr("id","subtotal_"+ (index));
               $(this).attr("id","subtotal1_"+ (index));
               $(this).attr("id","data_ke_"+ (index));
               // $(this).attr("id","subtotal_"+ (index));
               // $(this).attr("id","total1_"+ (index));
            }
              
        });

        
    }

    function calculate()
    {
        var subtotal_sum=0;
        $('.count_sub').each(function(index, el){
            subtotal_sum += +Math.floor($(this).val());
        });
        $('#subtotal_view').html('Rp '+numberToCurrency2(Math.floor(subtotal_sum)));
        $('#subtotal_view1').val(Math.floor(subtotal_sum))
    }

    function hitung()
    {
        var kembali;

        subtotal = currencyToNumber($('#subtotal_view1').val());  
        bayar = currencyToNumber($('#bayar').val());

        if(bayar>0)
        {
            kembali=bayar-subtotal;
            if(kembali>=0)
            {
                $('#kembali').val(numberToCurrency2(Math.floor(pecahan(kembali))))
            }
        }        
    }



    function reset()
    {
        $('#qty').val(0);
        $('#diskon').val(0);
        $('#harga').val(0);
        $('#stok').val(0);
        $('#stok1').val(0);
        $('#subtotal').val(numberToCurrency2(Math.floor(pecahan(0))));
        $('#subtotal1').val(numberToCurrency2(Math.floor(pecahan(0))));
        $('#total').val(numberToCurrency2(Math.floor(pecahan(0))));
        $('#total1').val(numberToCurrency2(Math.floor(pecahan(0))));
        $('#nama_barang').val('');   
        $('#id_obat').val(''); 
        $('#obat').val('');     
    }

    function addCommas(nStr){
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }

function delete_data(id)
{
  $.getJSON("modules/transaksi/aksi_transaksi.php?module=transaksi&act=cancel_transaksi",{id:id}, function(result){
    // $.each(result, function(i, data){
      // console.log(result);
      if(result.status==true)
      {
        toastr.success(result.msg,'Sukses');
      }
      else
      {
        toastr.warning(result.msg,'Peringatan');
      }
    // });
  });
}

function batal_transaksi(nota)
{
 if(confirm("Anda yakin akan membatalkan transaksi ini?")){
    $.getJSON("modules/transaksi/aksi_transaksi.php?module=transaksi&act=batal_transaksi",{nota:nota}, function(result){
    // $.each(result, function(i, data){
      // console.log(result);
      if(result.status==true)
      {
        toastr.success(result.msg,'Sukses');
        setTimeout(function(){location.reload()}, 2000);
    }
    else
    {
        toastr.warning(result.msg,'Peringatan');
    }
    // });
});
}
else
{
    return false;
}

}