### Znaczniki zewnętrzne
Znacznik | Opis
---|---
{*  *} | Wkleja zawartość jako kod HTML
{{  }} | Wkleja zawartość jako zwykły tekst

### Znaczniki wewnętrzne
Są prefiksami do zawartości zanczników zewnętrznych

Prefiks | Opis
---|---
Brak znacznika | Oznacza zmienną przekazywaną z backendu - np. {{ zmienna }} //Zawartość zmiennej
$ | Oznacza zmienną językową która zostanie przetłumaczona na język użytkownika zgodnie z plikami lokalizacyjnymi - {{ $hello_world }} //Witaj świecie!
@ | Oznacza import z pliku, jeśli nie poda się rozszerzenia, domyślnie wybierze .html - np. {* @templates/banner *} //Kod html z pliku templates/banner.html

*UWAGA : @ odwołuje się do root katalogu, jeśli chcemy uzyskać dostęp do plików pod nim należy zastosować w ścieżce '../' czyli zejście poziom niżej*