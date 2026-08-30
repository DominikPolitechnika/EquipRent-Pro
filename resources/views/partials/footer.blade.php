<link rel="stylesheet" href="{{ asset('style-foot.css') }}">
    {{-- ===== dodawane za pomocą include ===== --}}
<footer class="footer">
  <div class="footer-container">

    <div class="footer-col">
      <h3>EQUIPRENT PRO</h3>
      <p>
        zarządzanie wysokowydajnym sprzętem sportowym,
        standaryzowane rozwiązania wynajmu dla
        profesjonalnych projektów i zawodów.
      </p>
    </div>

    <div class="footer-col">
      <h4>FLOTA SPRZĘTU</h4>
      <ul>
        <li><a href="{{ url('/produkt/14') }}">Deska snowboardowa</a></li>
        <li><a href="{{ url('/produkt/24') }}">Rower szosowy</a></li>
        <li><a href="{{ url('/produkt/25') }}">Kask rowerowy</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>FIRMA</h4>
      <ul>
        <li><a href="{{ url('/o-nas') }}">O nas</a></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <a href="{{ url('/polityka-prywatnosci') }}">Polityka bezpieczeństwa</a>
    <a href="{{ url('/regulamin') }}">Regulamin</a>
  </div>
</footer>