import app from 'flarum/app';

import MailruSettingsModal from './components/MailruSettingsModal';

app.initializers.add('dem13n-auth-mailru', () => {
  app.extensionSettings['dem13n-auth-mailru'] = () => app.modal.show(new MailruSettingsModal());
});
