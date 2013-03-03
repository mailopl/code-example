 <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="hero-unit">
    <h1>Witaj w api.geum!</h1>
    <br /><p>
      apigeum to oprogramowanie, które umożliwia tworzenie płaskich zbiorów danych poprzez wygodny interfejs
      oraz na udostępnienie ich poprzez REST API (również odpłatnie).

    </p>
      <br />
    <p>
        <?php if ($currentUser): ?>
        <?php echo $this->Html->link('Sprawdź repozytoria! &raquo;', array('controller'=>'feeds','action'=>'index'), array('class'=>'btn btn-primary btn-large','escape'=>false)) ?>
        <?php else: ?>
            <?php echo $this->Html->link('Zarejestruj &raquo;', array('controller'=>'users','action'=>'register'), array('class'=>'btn btn-primary btn-large','escape'=>false)) ?>
        <?php endif; ?>
  </div>

  <!-- Example row of columns -->
  <div class="row">
    <div class="span4 <?php if ($currentUser): ?> disabled <?php endif ?>">
      <h2>1. Zarejestruj</h2>
       <p>Po rejestracji dostajesz możliwość tworzenia REST API oraz udostępnienia go (również odpłatnie).</p>
        <p> <?php echo $this->Html->link('Zarejestruj &raquo;', array('controller'=>'users','action'=>'register'), array('class'=>'btn','escape'=>false)) ?></p>
    </div>
    <div class="span4 <?php if (!$currentUser): ?> disabled <?php endif ?>">
      <h2>2. Utwórz API</h2>
       <p>Możesz utworzyć API ręcznie, lub poprzez import pliku CSV.</p>
      <p><?php echo $this->Html->link('Utwórz API', array('controller'=>'feeds','action'=>'add'), array('class'=>'btn','escape'=>false)) ?></p>
   </div>
    <div class="span4 <?php if (!$currentUser): ?> disabled <?php endif ?>">
      <h2>3. Zyskaj</h2>
      <p>Kiedy ktoś kupi dostęp do Twojego API, możesz to zobaczyć w menu Moje opcje &raquo; Klienci.</p>
      <p><?php echo $this->Html->link('Klienci', array('controller'=>'keys','action'=>'sold'), array('class'=>'btn ','escape'=>false)) ?></p>
    </div>
  </div>