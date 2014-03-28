<div class="content">
    <h1 style="color: red;">
        Something isn't right.
        
        <?php foreach($messages as $message) : ?>
          <?php  echo htmlspecialchars($message); ?>
         <?php endforeach ;?>
    </h1>
</div>
