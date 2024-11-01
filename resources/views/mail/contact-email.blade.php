Voici les détails du Contact qui a été généré depuis votre site Internet:
<br><br>
Prénom : {{ $contact->firstname }}<br>
Nom : {{ $contact->lastname }}<br>
Email : {{  $contact->email }}<br>
Newsletter : {{  $contact->newsletter ? 'oui' : 'non' }}<br>
Consentement donné : {{  $contact->agreement ? 'oui' : 'non'  }}<br>
<br>
Message :<br>
{{ $contact->message }}
