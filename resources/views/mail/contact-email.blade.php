Voici les détails du Contact qui a été généré depuis votre site Internet:
<br><br>
Prénom : {{ $contact->firstname }}<br>
Nom : {{ $contact->lastname }}<br>
Email : {{  $contact->email }}<br>
Newsletter : {{  $contact->newsletter }}<br>
Consentement donné : {{  $contact->agreement }}<br>
<br>
Message :<br>
{{ $contact->message }}
