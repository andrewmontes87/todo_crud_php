    <div class="footer container-wrap">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <p>&copy; <?php echo date("Y"); ?> | <a href="https://fossilfreefunds.org" target="_blank" title="fossilfreefunds.org">fossilfreefunds.org</a> | <a href="http://www.asyousow.org" target="_blank" title="www.asyousow.org">asyousow.org</a></p>
          </div>
        </div>
      </div>
    </div>
    <script
      src="https://code.jquery.com/jquery-2.2.4.min.js"
      integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
      crossorigin="anonymous"></script>
    <script 
      src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
      integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
      crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/5fe6d738dc.js"></script>
    <script>
      $('#investment-strategy-select').on('change', function(){
        var selected_option = $('#investment-strategy-select option:selected');
        if (selected_option[0].value === 'Other') {
          $('#other-investment-strategy-select').removeClass('hidden');
        } else {
          $('#other-investment-strategy-select').addClass('hidden');
        }
      });
      $('#asset-manager-select').on('change', function(){
        var selected_option = $('#asset-manager-select option:selected');
        if (selected_option[0].value === 'Other') {
          $('#other-asset-manager-select').removeClass('hidden');
        } else {
          $('#other-asset-manager-select').addClass('hidden');
        }
      });
    </script>
  </body>
</html>
<?php
  // 5. Close database connection
  if (isset($connection)) {
    mysqli_close($connection);
  }
?>
