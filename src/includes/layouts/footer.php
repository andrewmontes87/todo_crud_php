    <div class="footer container-wrap">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <h4>Source code</h4>
            <p><a href="https://github.com/andrewmontes87/todo_crud_php" title="https://github.com/andrewmontes87/todo_crud_php" target="_blank">https://github.com/andrewmontes87/todo_crud_php</a></p>

            <p>&copy; <?php echo date("Y"); ?> | <a href="http://www.asyousow.org" target="_blank" title="www.asyousow.org">asyousow.org</a></p>
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
      //
    </script>
  </body>
</html>
<?php
  // 5. Close database connection
  if (isset($connection)) {
    mysqli_close($connection);
  }
?>
