import SettingsModal from 'flarum/components/SettingsModal';

export default class MailruSettingsModal extends SettingsModal {
  className() {
    return 'MailruSettingsModal Modal--small';
  }

  title() {
    return app.translator.trans('dem13n-auth-mailru.admin.mailru_settings.title');
  }

  form() {
    return [
      <div className="Form-group">
        <label>{app.translator.trans('dem13n-auth-mailru.admin.mailru_settings.app_desc', { a: <a href="https://api.mail.ru/sites/my/add" target="_blank" /> })}</label>
        <label>{app.translator.trans("dem13n-auth-mailru.admin.mailru_settings.app_p")}</label>
        <b>{document.location.origin + "/auth/mailru"}</b>
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('dem13n-auth-mailru.admin.mailru_settings.app_id_label')}</label>
        <input className="FormControl" bidi={this.setting('dem13n-auth-mailru.app_id')} />
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('dem13n-auth-mailru.admin.mailru_settings.app_secret_label')}</label>
        <input className="FormControl" bidi={this.setting('dem13n-auth-mailru.app_secret')} />
      </div>
    ];
  }
}
