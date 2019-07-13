import { extend } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';

app.initializers.add('dem13n-auth-mailru', () => {
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add('mailru',
      <LogInButton
        className="Button LogInButton--mailru"
        icon="fas fa-at"
        path="/auth/mailru">
        <span className="LoginText">{app.translator.trans('dem13n-auth-mailru.forum.login_with_mailru_button')}</span>
      </LogInButton>
    );
  });
});
