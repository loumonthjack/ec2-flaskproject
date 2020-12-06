from main_project import app, db, login_manager
from datetime import datetime
from itsdangerous import TimedJSONWebSignatureSerializer as Serializer
from flask_login import UserMixin

@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

class User(db.Model, UserMixin):
    __tablename__ = 'User'
    id = db.Column(db.Integer, primary_key=True)
    email_address = db.Column(db.String(120), unique=True, nullable=False)
    password = db.Column(db.String(60), nullable=False)
    date_posted = db.Column(db.DateTime, nullable=False, default=datetime.utcnow)
    bank_account_id = db.Column(db.Integer, nullable=True, default='BankAccount.id') 
      
    def get_reset_token(self, expires_sec=1800):
        s = Serializer(app.config['SECRET_KEY'], expires_sec)
        return s.dumps({'user_id': self.id}).decode('utf-8')

    @staticmethod
    def verify_reset_token(token):
        s = Serializer(app.config['SECRET_KEY'])
        user_id = s.loads(token)['user_id']
        return User.query.get(user_id)

    def __repr__(self):
        return f"User('{self.email_address}')"

class BankAccount(db.Model):
    __tablename__ = 'BankAccount'
    id = db.Column(db.Integer, primary_key=True)
    account_number = db.Column(db.Integer, unique=True, nullable=False)
    routing_number = db.Column(db.Integer, nullable=False)
    account_owner = db.Column(db.Integer, db.ForeignKey('User.id'))
    #owner = db.relationship('User', back_populates='bank_account_id')

    def __repr__(self):
        return f"BankAccount'{self.account_number}')"
