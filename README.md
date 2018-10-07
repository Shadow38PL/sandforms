### Specyfikacja templatki ###
{{  }} - Tag w którym umieszczamy wszystkie żeczy związane z templatką.

Typy zmiennych:
@ - Import z pliku np: @templatki/plik - Wkleja zawartość pliku o podanej ścieżce (jeśli nie podamy rozszerzenia domyślniie będzie szukany plik.html).
$ - Zmienna językowa np: $main_page - Wkleja tłumaczenie dla zmiennej z pliku językowego.
# - Stała np: #ver - wkleja stałą utworzoną w pliku konfiguracyjnym.
bez prefiksu - Zmienna przekazana przez funkcje render - wkleja zawartość zmiennej.

Operatory:
? - Operator warunkowy np: {{ warunek ? templatka }} - Jeśli zmienna warunkowa jest ustawiona i nie równa się fałsz lub jest pusta to zostanie wyrenderowana templatka następująca po operatorze warunkowym. Templatka może być zapisana bezpośrednio w tagu lub może być importem tzn. @templatka.
=> - Operator mapowania np: {{ tablica => templatka }} - Renderuje templatkę używając wartości tablicy jako parametrów. Jeśli tablica jest sekwencyjna to templatka renderowana jest dla każdej z jej wartości. Jeśli wartośćiami tej tablicy nie są tablice asocjacyjne to wartość tą w templatce zczytujemy jako zmienna 'value'. Templatka może być zapisana bezpośrednio w tagu lub może być importem tzn. @templatka.

Komentarze:
Zwyczajne komentaże HTML (<!-- -->) zostaną usunięte.
By zachować komentaż trzeba uzyć specjalnego komentarza wymuszonego (<!--! -->)

### Obsługa rendera ###
Renderowanie templatki z pliku wywołujemy funkcją render($ścieżka, $parametry), gdzie $ścieżka jest ścieżką od webroota zdefiniowanego w pliku konfiguracyjnym a $parametry są tabicą asocjacyjną gdzie wartość będzie przypisana zmiennej o nazwie klucza.
Renderowanie templatki ze zmiennej wywołujemy funkcją renderContent($zmienna, $parametry). Gdzie zmienna jest tekstem templatki.

### Plik konfiguracyjny ###
Jest plikiem php zawierającym globalne parametry używane przez render.

$webRoot - Jest ścieżką startową dla wszystkich ścieżek podawanych funkcji render i dla importów.
$langFolder - Jest ścieżką folderu z plikami językowymi zależną od $webRoot.

$defaultLanguage - Jest domyślnym językiem tłumaczenia jeśli nie ma tłumaczenia w języku użytkownika.
$langCookieName - Jest nazwą ciasteczka w którym przechowywany jest język wybrany przez użytkownika.

$constants - Jest tablicą asocjacyjną zawierającą stałe używane przy renderowaniu templatek. Klucz jest nazwą zmiennej a wartość jej wartością.

### Pliki językowe ###
Zapisywane są w fomacie '.json' w folderze językowym.

Klucze są nazwami zmiennych językowych a wartości tłumaczeniami.

### Konwencja jęzków ###
Pliki językowe i domyślny język nzywane są w formacie ogólnym np. en lub bardziej sczegółowo np. en_US. Jeśli nie ma tłumaczenia dla języka szczegułowego np. en_GB to zostanie zastosowane tłumaczenie ogólne np. en. Jeśli nie ma i takiego tłumaczenia zastosowane zostanie tłumaczenie domyślne ustawione w pliku konfiguracyjnym.