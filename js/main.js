function checkQlf()
{

    if($('#certificationdate1').val() != '' || $('#expirationdate1').val() != '' || $('#certificationdate2').val() != '' || $('#expirationdate2').val() != '' || $('#certificationdate3').val() != '' || $('#expirationdate3').val() != '' || $('#craneoperator').is(":checked") == true || $('#qlfcertified1').is(":checked") == true || $('#qlfsignalperson').is(":checked") == true || $('#qlfcertified2').is(":checked") == true || $('#qlfrigger').is(":checked") == true || $('#qlfcertified3').is(":checked") == true || $('#qlfmechanic').is(":checked") == true || $('#qlfelectromechanic').is(":checked") == true || $('#qlfinspector').is(":checked") == true)
    {
        $('.laborRate :input').each(function(){
           $(this).attr('required', '');
        })
    }
    else
    {
        $('.laborRate :input').each(function(){
           $(this).removeAttr('required');
        })
    }

}

function checkDate()
{
  var startdate=$('#startdate').val();
  var estimatedate=$('#estimatedate').val();
  var deliverydate=$('#deliverydate').val();
  var actualenddate=$('#actualenddate').val();
  $('#showerror').hide();
  setTimeout(function() {
  if(startdate != "")
  {
    var sdobj = new Date(startdate).getTime();
    if(estimatedate != "")
    {
      var edobj = new Date(estimatedate).getTime();
      if( edobj < sdobj)
      {
        $('#showerror').show();
        $('#estimatedate').val('');
      }
    }

    if(deliverydate != "")
    {
      var ddobj = new Date(deliverydate).getTime();

      if( ddobj < sdobj)
      {
        $('#showerror').show();
        $('#deliverydate').val('');
      }
    }

    if(actualenddate != "")
    {
      var adobj = new Date(actualenddate).getTime();
      if( adobj < sdobj)
      {
        $('#showerror').show();
        $('#actualenddate').val('');
      }
    }

  }

   }, 10);
}
function checkVal()
{
  var sectype = $('#vehsection').val();
  var vehid = $('#vehicle').val();

  if(vehid == "")
  {
    alert("please select first Vehicle");

    return;
  }
  else
  {
    if(sectype != "")
    {
      $.ajax({
                url: 'include/getData.php',
                type: 'POST',
                dataType: 'html',
                data: "reqtype=getlastfuelodometer&id="+vehid+"&sectype="+sectype, //get model dan ukuran
                success: function (data) {
                  if(data != 0)
                  {
                    var expData = data.split(":");
                    $('#odometer').val(expData[0]);
                    $('#enginehour').val(expData[1]);
                    $('#lastodm').val(expData[0]);
                    $('#lastenghr').val(expData[1]);

                    $('#odometer').css('background-color' , '#FFFFEE');
                    $('#enginehour').css('background-color' , '#FFFFEE');
                  }
                },
                error: function (e) {
                    //called when there is an error
                    console.log(e.message);
                }
            });
    }
  }
}

function getCustomerPrice()
{
  var weighttocollect = $('#weighttocollect').val();
  var customer = $('#customer').val();

  if(customer == "")
  {
    alert("please select Customer first");

    return;
  }
  else if(weighttocollect == "")
  {
    alert("Weight to collect is empty");

    return;
  }
  else
  {

      $.ajax({
                url: 'include/getData.php',
                type: 'POST',
                dataType: 'html',
                data: "reqtype=getcustomerprice&customer="+customer+"&weighttocollect="+weighttocollect, //get model dan ukuran
                success: function (data) {

                  if(data != 0)
                  {
                    var pricVal = parseFloat(data).toFixed(2);
                    $('#custompricerate').val(pricVal);
                    var tpay = (weighttocollect * data).toFixed(2);
                    $('#totaltopay').val(tpay);
                    $('#btnPackEdit').show();
                  }
                  else
                  {
                    alert("Weight is bigger than membership range");
                    $('#btnPackEdit').hide();
                    $('#custompricerate').val('');
                    $('#totaltopay').val('');
                  }
                },
                error: function (e) {
                    //called when there is an error
                    console.log(e.message);
                }
            });
  }
}

function checkCompany()
{
  var usertype = $('#usertype').val();
  var distributor = $('#distributor').val();
  if(usertype > 0 && distributor > 0)
  {
    if(usertype == 1 && distributor != 1)
    {
      alert("Distributor must be CGL Storage for Master Role");
        $('#distributor').val('').trigger("chosen:updated");
    }
  }
}

function checkOdometer(odmeter)
{
   var lastodmeter = $('#lastodm').val();
   var lastSection = $('#lastSection').val();


   if(odmeter > 0 && odmeter <= lastodmeter)
   {
        alert("Odometer values can not be greater than last odometer");
        $('#odometer').val(lastodmeter);
        $('#odometer').focus();
   }

}
function checkEngineHr(enginehr)
{
   var lastenghr = $('#lastenghr').val();

   if(enginehr > 0 && enginehr <= lastenghr)
   {
        alert("Engine Hour values can not be greater than last Engine Hour");
        $('#enginehour').val(lastenghr);
        $('#enginehour').focus();
   }

}
function getOptionsData(el, type, id) {
        if (el.value === "") {
            $(el).siblings("input[name=model]").val("");
        } else {
            $.ajax({
                url: 'include/getData.php',
                type: 'POST',
                dataType: 'html',
                data: "reqtype="+type+"&id="+el, //get model dan ukuran
                success: function (data) {

                    if(type == "getlastodometer")
                    {
                      alert(data);
                       if(data != 0)
                       {
                         var expData = data.split(":");
                         $('#'+id).val(expData[0]);
                         $('#lastSection').val(expData[1]);
                         $('#'+id).css('background-color' , '#FFFFEE');
                         $('#lastodm').val(expData[0]);
                      }
                    }
                    else if(type == "getlastenginehour")
                    {
                        if(data != 0)
                       {
                         var expData = data.split(":");
                         $('#'+id).val(expData[0]);
                         $('#lastSection').val(expData[1]);
                         $('#'+id).css('background-color' , '#FFFFEE');
                         $('#lastenghr').val(expData[0]);
                       }
                    }
                    else if(type == "showcustomerlink")
                    {
                       $('#'+id).html(data);
                       $('#'+id).show();
                    }
                    else
                    {
                        $('#'+id).html(data);
                        //$('#'+id).chosen();
                        $('#'+id).val('').trigger("chosen:updated");
                    }
                    //$(el).closest('.barang_in').find("input[name='model']").val(data.nama_model + " " + "(" + data.ukuran + ")");//get the parent element and then find the input
                },
                error: function (e) {
                    //called when there is an error
                    console.log(e.message);
                }
            });
        }
    }
    var currentRow = null;
    function fillLine()
    {
        var pricperpound = parseFloat($('#pricperpound').val());
        var initialrange = parseFloat($('#initialrange').val());
        var lastrange = parseFloat($('#lastrange').val());
        var data = pricperpound+"::::"+initialrange+"::::"+lastrange;
        var rowData = "<input type='hidden' name='h1[]' value='"+data+"'>";
        var btnhtml = "<td><a onclick=\"editLine()\"  data-toggle='modal' data-target=\"#myModal\"><i class='glyphicon glyphicon-pencil'></i></a>&nbsp;&nbsp;<i onclick='rm()' class='glyphicon glyphicon-remove'></i></td>";
        var new_row = "<tr>"+rowData+"<td>"+pricperpound.toFixed(2)+"</td><td>"+initialrange.toFixed(2)+"</td><td>"+lastrange.toFixed(2)+"</td>"+btnhtml+"</tr>";
        if(currentRow)
        {
          $(".tableline").find($(currentRow)).replaceWith(new_row);
          currentRow = null;
        }
        else
        {
          $(".tableline").append(new_row);
        }

        $("#myModal .close").click();
    }

    function rm() {

      $(event.target).closest("tr").remove();
      updateTotal();
    }

    function removeRecpt() {

      $(event.target).closest("tr").remove();
      //updateTotal();
    }

    function editLine()
    {
      var selectedRow = $(event.target).closest("tr");
      var pricperpound = selectedRow.find('td:eq(0)').text();
      var initialrange = selectedRow.find('td:eq(1)').text();
      var lastrange = selectedRow.find('td:eq(2)').text();
      $('#pricperpound').val(pricperpound);
      $('#initialrange').val(initialrange);
      $('#lastrange').val(lastrange);
      currentRow = selectedRow;
    }

    function getVolume()
    {
      var l = $('#length').val();
      var w = $('#width').val();
      var h = $('#height').val();
      var v = $('#variable').val();
      var volum = ((l * w * h ) / v).toFixed(2);
      $('#volume').val(volum);
    }

    function getWeightToCollect()
    {
      var vol = $('#volume').val();
      var wlb = $('#widthlb').val();
      var wc = (parseFloat(vol) > parseFloat(wlb)) ? parseFloat(vol) : parseFloat(wlb);
      //wc = Math.ceil(wc); original

      function redondearDecimales(numero, decimales) {  // funcion que si redondea bien hacia arriba
          numeroRegexp = new RegExp('\\d\\.(\\d){' + decimales + ',}');   // Expresion regular para numeros con un cierto numero de decimales o mas
          if (numeroRegexp.test(numero)) {         // Ya que el numero tiene el numero de decimales requeridos o mas, se realiza el redondeo
              return Number(numero.toFixed(decimales));
          } else {
              return Number(numero.toFixed(decimales)) === 0 ? 0 : numero;  // En valores muy bajos, se comprueba si el numero es 0 (con el redondeo deseado), si no lo es se devuelve el numero otra vez.
          }
      }

      wc = redondearDecimales(wc, 1)
      //wc = Math.round(wc * 100) / 100; funciona pero no redondea hacia arriba
      $("#weighttocollect").val(wc);

      getCustomerPrice();
    }

    function emptyLine()
    {
       $('#pricperpound').val('');
      $('#initialrange').val('');
      $('#lastrange').val('');
    }



    function getPackageList()
    {
        var customer = $('#customer').val();
        if(customer != "")
        {
          $.ajax({
                    url: 'include/getData.php',
                    type: 'POST',
                    dataType: 'json',
                    data: "reqtype=getpackages&id="+customer, //get model dan ukuran
                    success: function (data) {
                      var data  = data;

                      if(data.length > 0)
                      {
                        var jsonData = data;//JSON.stringify(data);
                        console.log(jsonData);
                        var html='<a  class="btn btn-primary" onclick="selectaLLPackage(\''+jsonData+'\')">All</a><table class="table table-striped b-t b-light">';
                                html+='<thead>';
                                html+='<tr>';
                                  html+='<th>Pieces</th>';
                                  html+='<th>Tracking</th>';
                                  html+='<th>Shipper</th>';
                                  // html+='<th>Dimension</th>';
                                  // html+='<th>Weight</th>';
                                  // html+='<th>Price Per Pound</th>';
                                  // html+='<th>Price</th>';
                                html+='</tr>';
                              html+='</thead>';
                        for (var i = 0; i < data.length; i++) {

                          var isPackageExist = $('#pieces'+data[i]['id']).val();

                          if(isPackageExist != undefined)
                            continue;

                          html+='<tr>';
                          html+='  <td><input type="text" size="5" class="form-control" id="box'+data[i]['id']+'"></td>';
                          html+='  <td><a onclick="selectPackage('+data[i]['id']+', \''+data[i]['trackingno']+'\', '+data[i]['custompricerate']+', '+data[i]['length']+', '+data[i]['width']+', '+data[i]['height']+', '+data[i]['widthlb']+', '+data[i]['totaltopay']+')" >'+data[i]['trackingno']+'</a></td>';
                          html+='  <td>'+data[i]['shipper']+'</td>';
                          // html+='  <td>'+data[i]['trackingno']+'</td>';
                          // html+='  <td>'+data[i]['trackingno']+'</td>';
                          // html+='  <td>'+data[i]['trackingno']+'</td>';
                          html+='</tr>';
                        }
                        html+='</table>';

                        $('#packagedata').html(html);
                      }
                    },
                    error: function (e) {
                        //called when there is an error
                        console.log(e.message);
                    }
                });
        }


        $("#myModal .close").click();
    }

    function selectaLLPackage(data)
    {

      //console.log(data, $.parseJSON(data));

    }

    function selectPackage(id, trackno, priceperpound, length, width, height, widthlb, price)
    {
        var pieces = $('#box'+id).val();
        if(pieces == "")
          pieces = 1;
        var ptotal = $('#ptotal').html();
        var wbtotal = $('#wbtotal').html();
        var subtotal = $('#subtotal').html();
        //var othtotal = $('#othtotal').html();
        var gtotal = $('#gtotal').html();
        if(ptotal != "")
          ptotal = parseFloat(ptotal) + parseFloat(pieces);
        else
          ptotal = pieces;

        if(wbtotal != "")
          wbtotal = parseFloat(wbtotal) + parseFloat(widthlb);
        else
          wbtotal = widthlb;

        if(subtotal != "")
          subtotal = parseFloat(subtotal) + parseFloat(price);
        else
          subtotal = price;

        // if(othtotal != "")
        //   othtotal = parseFloat(othtotal) + parseFloat(priceperpound);
        // else
        //   othtotal = priceperpound;

        if(gtotal != "")
          gtotal = parseFloat(gtotal) + parseFloat(price);
        else
          gtotal = parseFloat(price);

        $('#ptotal').html(ptotal);
        $('#wbtotal').html(wbtotal);
        $('#subtotal').html(subtotal.toFixed(2));
        //$('#othtotal').html(othtotal);
        $('#gtotal').html(gtotal.toFixed(2));

        length = length.toFixed(2);
        width = width.toFixed(2);
        height = height.toFixed(2);
        widthlb = widthlb.toFixed(2);
        var data = id+"::::"+pieces+"::::"+widthlb;
        var rowData = "<input type='hidden' name='h1[]' value='"+data+"'>";
        var btnhtml = "<td><i onclick='rm()' class='glyphicon glyphicon-remove'></i></td>";
        var new_row = "<tr>"+rowData+"<td><input type='text' size='5' onblur='updateTotal()' class='form-control' name='pieces"+id+"' id='pieces"+id+"' value='"+pieces+"'></td><td>"+trackno+"</td><td>"+priceperpound+"</td><td>"+length+"</td><td>"+width+"</td><td>"+height+"</td><td>"+widthlb+"</td><td><input type='text' size='5' class='form-control' id='price"+id+"' name='price"+id+"' onblur='updateTotal()' value='"+price+"'></td>"+btnhtml+"</tr>";
        if(currentRow)
        {
          $(".tableline").find($(currentRow)).replaceWith(new_row);
          currentRow = null;
        }
        else
        {
          $(".tableline").append(new_row);
        }

        $("#myModal .close").click();
    }

    function addReceiptRow()
    {
      var btnhtml = "<td><i onclick='removeRecpt()' class='glyphicon glyphicon-remove'></i></td>";
      var new_row = "<tr><td><input type='text'  class='form-control' id='description[]' name='description[]'  ></td><td><input type='text' size='5' onblur='updateRecptTotal()' class='form-control' name='recptAmount[]' id='recptAmount[]'></td>"+btnhtml+"</tr>";
        if(currentRow)
        {
          $(".tableline").find($(currentRow)).replaceWith(new_row);
          currentRow = null;
        }
        else
        {
          $(".tableline").append(new_row);
        }
    }

    function updateTotal()
    {

        var totalOrgPrice = 0;
        var totalOrgPiece = 0;
        var totalwidthlb = 0;
        var othval = $("#otherval").val();
        $('input[name^="h1"]').each( function() {
            var spltVal = this.value.split("::::");
            var ptotal = $("#pieces"+spltVal[0]).val();
            var prctotal = $("#price"+spltVal[0]).val();
            totalOrgPiece += parseFloat(ptotal);
            totalwidthlb += parseFloat(spltVal[2]);
            totalOrgPrice += parseFloat(prctotal);
        });

        if(othval != "")
          var gtotalval = parseFloat(othval) + parseFloat(totalOrgPrice);
        else
          var gtotalval = parseFloat(totalOrgPrice);
        $("#ptotal").html(totalOrgPiece);
        $("#wbtotal").html(totalwidthlb.toFixed(2));
        $("#subtotal").html(totalOrgPrice.toFixed(2));
        $("#gtotal").html(gtotalval);
    }
