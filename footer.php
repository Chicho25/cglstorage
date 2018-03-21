        <div class="footer">
            <div class="pull-right">
                <strong></strong>
            </div>
            <div>
                <strong></strong> 
            </div>
        </div>

    </div>
</div>



    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    <!-- Chosen -->
    <script src="js/plugins/chosen/chosen.jquery.js"></script>
    <!-- Switchery -->
   <script src="js/plugins/switchery/switchery.js"></script>
   <!-- Jasny -->
    <script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>
    <!-- FooTable -->
    <script src="js/plugins/footable/footable.all.min.js"></script>
    <!-- Data picker -->
   <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Clock picker -->
    <script src="js/plugins/clockpicker/clockpicker.js"></script>
    <!-- blueimp gallery -->
    <script src="js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
    <script src="js/plugins/dataTables/datatables.min.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQTpXj82d8UpCi97wzo_nKXL7nYrd4G70"></script>
    <!-- Page-Level Scripts -->
    <script>

        $(document).ready(function() {


            $.fn.oldChosen = $.fn.chosen
            $.fn.chosen = function(options) {
              var select = $(this)
                , is_creating_chosen = !!options

              if (is_creating_chosen && select.css('position') === 'absolute') {
                // if we are creating a chosen and the select already has the appropriate styles added
                // we remove those (so that the select hasn't got a crazy width), then create the chosen
                // then we re-add them later
                select.removeAttr('style')
              }

              var ret = select.oldChosen(options)

              // only act if the select has display: none, otherwise chosen is unsupported (iPhone, etc)
              if (is_creating_chosen && select.css('display') === 'none') {
                // https://github.com/harvesthq/chosen/issues/515#issuecomment-33214050
                // only do this if we are initializing chosen (no params, or object params) not calling a method
                select.attr('style','display:visible; position:absolute; clip:rect(0,0,0,0)');
                select.attr('tabindex', -1);
              }
              return ret
            }
            $('.chosen-select').chosen({width: "100%"});



            $('.dataTables-example').DataTable({
                dom: 'Bfrtip',
      "sPaginationType": "full_numbers",
      "bFilter": false,
      "bProcessing": true,
        buttons: [
            // 'csv', 'excel', 'pdf'
        ]

            });

            $('.statement-acc').DataTable({
                dom: 'Bfrtip',
              "bPaginate": false,
              "bFilter": false,
              "bProcessing": true,
                buttons: [
                    // 'csv', 'excel', 'pdf'
                ]

            });

            $('.clockpicker').clockpicker();

            $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
            $('#data_2 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
            $('#data_3 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
            $('#data_4 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
            $('#data_5 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
            $('#data_6 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });

            $('.dual_select').bootstrapDualListbox({
                selectorMinimalHeight: 160
            });

            $('.footable').footable();
            var elem = document.querySelector('.js-switch');
            var switchery = new Switchery(elem, { color: '#1AB394' });



        });
        var geocoder;
        var map;
        var marker;
        function myMap() {
            // Options for Google map
            // More info see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
           var mapProp= {
                center:new google.maps.LatLng(51.508742,-0.120850),
                zoom:13,
            };
            map=new google.maps.Map(document.getElementById("map1"),mapProp);

            geocoder = new google.maps.Geocoder();

            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });
            if(document.getElementById("address1").value != "")
                setMarket();
        }

        function placeMarker(location) {



            if (marker == undefined){
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
            }
            else{
                marker.setPosition(location);
            }
            map.setCenter(location);
            getAddress(location);
        }

        function setMarket() {

          var address = document.getElementById("address1").value;

            geocoder.geocode({
                'address': address
            }, function (results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    map.setCenter(results[0].geometry.location);

                    marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });


                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }

        function getAddress(latLng) {
            geocoder.geocode( {'latLng': latLng},
              function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                  if(results[0]) {
                    document.getElementById("address1").value = results[0].formatted_address;
                  }
                  else {
                    document.getElementById("address1").value = "";
                  }
                }
                else {
                  document.getElementById("address1").value = status;
                }
              });
            }
    </script>

</body>

</html>
