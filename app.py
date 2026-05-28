#!/usr/bin/env python3
"""
Site Institucional do Vereador Douglas Souto (Dodô)
Flask Application
"""

from flask import Flask, render_template, request, redirect, url_for, session, flash, send_from_directory
from flask_sqlalchemy import SQLAlchemy
from werkzeug.security import generate_password_hash, check_password_hash
from werkzeug.utils import secure_filename
from datetime import datetime
import os
import uuid

app = Flask(__name__)
app.config['SECRET_KEY'] = 'dodo-mucuri-ba-2024-secret-key'
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///vereador_dodo.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['UPLOAD_FOLDER'] = '/workspace/uploads'
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB max

db = SQLAlchemy(app)

# ==================== MODELOS DO BANCO DE DADOS ====================

class Admin(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(100), nullable=False)
    email = db.Column(db.String(120), unique=True, nullable=False)
    senha_hash = db.Column(db.String(255), nullable=False)
    criado_em = db.Column(db.DateTime, default=datetime.utcnow)

class Noticia(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    titulo = db.Column(db.String(200), nullable=False)
    subtitulo = db.Column(db.String(300))
    texto = db.Column(db.Text, nullable=False)
    categoria = db.Column(db.String(50))
    imagem_capa = db.Column(db.String(255))
    publicado = db.Column(db.Boolean, default=False)
    visualizacoes = db.Column(db.Integer, default=0)
    criado_em = db.Column(db.DateTime, default=datetime.utcnow)
    atualizado_em = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

class NoticiaGaleria(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    noticia_id = db.Column(db.Integer, db.ForeignKey('noticia.id'), nullable=False)
    imagem = db.Column(db.String(255), nullable=False)
    ordem = db.Column(db.Integer, default=0)

class CategoriaNoticia(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(50), unique=True, nullable=False)
    slug = db.Column(db.String(50), unique=True)

class AcaoParlamentar(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    titulo = db.Column(db.String(200), nullable=False)
    descricao = db.Column(db.Text, nullable=False)
    tipo = db.Column(db.String(50), nullable=False)  # projeto, indicacao, requerimento, fiscalizacao
    categoria = db.Column(db.String(50))  # saude, educacao, infraestrutura, etc.
    imagem = db.Column(db.String(255))
    documento = db.Column(db.String(255))
    data_protocolo = db.Column(db.Date)
    status = db.Column(db.String(50), default='Em análise')
    criado_em = db.Column(db.DateTime, default=datetime.utcnow)

class FotoGaleria(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    titulo = db.Column(db.String(100))
    descricao = db.Column(db.Text)
    imagem = db.Column(db.String(255), nullable=False)
    categoria = db.Column(db.String(50))
    destaque = db.Column(db.Boolean, default=False)
    criado_em = db.Column(db.DateTime, default=datetime.utcnow)

class MensagemContato(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(100), nullable=False)
    telefone = db.Column(db.String(20))
    bairro = db.Column(db.String(100))
    assunto = db.Column(db.String(100))
    mensagem = db.Column(db.Text, nullable=False)
    lido = db.Column(db.Boolean, default=False)
    criado_em = db.Column(db.DateTime, default=datetime.utcnow)

class Configuracao(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    chave = db.Column(db.String(50), unique=True, nullable=False)
    valor = db.Column(db.Text)

# ==================== FUNÇÕES AUXILIARES ====================

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in {'png', 'jpg', 'jpeg', 'gif', 'pdf', 'doc', 'docx'}

def get_config(chave, default=''):
    config = Configuracao.query.filter_by(chave=chave).first()
    return config.valor if config else default

# ==================== ROTAS PÚBLICAS ====================

@app.route('/')
def index():
    noticias_recentes = Noticia.query.filter_by(publicado=True).order_by(Noticia.criado_em.desc()).limit(6).all()
    acoes_destaque = AcaoParlamentar.query.order_by(AcaoParlamentar.criado_em.desc()).limit(4).all()
    fotos_destaque = FotoGaleria.query.filter_by(destaque=True).limit(4).all()
    return render_template('index.html', 
                         noticias=noticias_recentes, 
                         acoes=acoes_destaque, 
                         fotos=fotos_destaque,
                         pagina='inicio')

@app.route('/sobre')
def sobre():
    return render_template('sobre.html', pagina='sobre')

@app.route('/atuacao')
def atuacao():
    categoria = request.args.get('categoria', '')
    tipo = request.args.get('tipo', '')
    
    query = AcaoParlamentar.query
    if categoria:
        query = query.filter_by(categoria=categoria)
    if tipo:
        query = query.filter_by(tipo=tipo)
    
    acoes = query.order_by(AcaoParlamentar.criado_em.desc()).all()
    categorias = ['Saúde', 'Educação', 'Infraestrutura', 'Assistência Social', 'Agricultura', 'Obras Públicas', 'Fiscalização']
    tipos = ['Projeto de Lei', 'Indicação', 'Requerimento', 'Fiscalização', 'Pedido de Providência']
    
    return render_template('atuacao.html', acoes=acoes, categorias=categorias, tipos=tipos, 
                         categoria_filtro=categoria, tipo_filtro=tipo, pagina='atuacao')

@app.route('/noticias')
def noticias():
    categoria = request.args.get('categoria', '')
    busca = request.args.get('busca', '')
    
    query = Noticia.query.filter_by(publicado=True)
    if categoria:
        query = query.filter_by(categoria=categoria)
    if busca:
        query = query.filter(Noticia.titulo.ilike(f'%{busca}%') | Noticia.texto.ilike(f'%{busca}%'))
    
    noticias_lista = query.order_by(Noticia.criado_em.desc()).all()
    categorias = db.session.query(Noticia.categoria).distinct().filter(Noticia.categoria != None).all()
    categorias = [c[0] for c in categorias if c[0]]
    
    return render_template('noticias.html', noticias=noticias_lista, categorias=categorias,
                         categoria_filtro=categoria, busca_filtro=busca, pagina='noticias')

@app.route('/noticia/<int:id>')
def noticia(id):
    noticia = Noticia.query.get_or_404(id)
    noticia.visualizacoes += 1
    db.session.commit()
    galeria = NoticiaGaleria.query.filter_by(noticia_id=id).order_by(NoticiaGaleria.ordem).all()
    return render_template('noticia.html', noticia=noticia, galeria=galeria, pagina='noticias')

@app.route('/galeria')
def galeria():
    categoria = request.args.get('categoria', '')
    query = FotoGaleria.query
    if categoria:
        query = query.filter_by(categoria=categoria)
    fotos = query.order_by(FotoGaleria.criado_em.desc()).all()
    categorias = db.session.query(FotoGaleria.categoria).distinct().filter(FotoGaleria.categoria != None).all()
    categorias = [c[0] for c in categorias if c[0]]
    return render_template('galeria.html', fotos=fotos, categorias=categorias, 
                         categoria_filtro=categoria, pagina='galeria')

@app.route('/transparencia')
def transparencia():
    documentos = AcaoParlamentar.query.filter(AcaoParlamentar.documento != None).all()
    return render_template('transparencia.html', documentos=documentos, pagina='transparencia')

@app.route('/contato', methods=['GET', 'POST'])
def contato():
    if request.method == 'POST':
        nome = request.form.get('nome')
        telefone = request.form.get('telefone')
        bairro = request.form.get('bairro')
        assunto = request.form.get('assunto')
        mensagem = request.form.get('mensagem')
        
        if nome and mensagem:
            msg = MensagemContato(nome=nome, telefone=telefone, bairro=bairro, 
                                 assunto=assunto, mensagem=mensagem)
            db.session.add(msg)
            db.session.commit()
            flash('Mensagem enviada com sucesso! Em breve entraremos em contato.', 'success')
            return redirect(url_for('contato'))
        else:
            flash('Por favor, preencha os campos obrigatórios.', 'error')
    
    return render_template('contato.html', pagina='contato')

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

# ==================== ROTAS DO ADMIN ====================

@app.route('/admin/login', methods=['GET', 'POST'])
def admin_login():
    if request.method == 'POST':
        email = request.form.get('email')
        senha = request.form.get('senha')
        
        admin = Admin.query.filter_by(email=email).first()
        if admin and check_password_hash(admin.senha_hash, senha):
            session['admin_id'] = admin.id
            session['admin_nome'] = admin.nome
            flash('Login realizado com sucesso!', 'success')
            return redirect(url_for('admin_dashboard'))
        else:
            flash('Email ou senha incorretos.', 'error')
    
    return render_template('admin/login.html')

@app.route('/admin/logout')
def admin_logout():
    session.pop('admin_id', None)
    session.pop('admin_nome', None)
    flash('Logout realizado com sucesso!', 'success')
    return redirect(url_for('admin_login'))

@app.route('/admin')
def admin_dashboard():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    total_noticias = Noticia.query.count()
    total_acoes = AcaoParlamentar.query.count()
    total_fotos = FotoGaleria.query.count()
    total_mensagens = MensagemContato.query.count()
    mensagens_nao_lidas = MensagemContato.query.filter_by(lido=False).count()
    
    return render_template('admin/dashboard.html', 
                         total_noticias=total_noticias,
                         total_acoes=total_acoes,
                         total_fotos=total_fotos,
                         total_mensagens=total_mensagens,
                         mensagens_nao_lidas=mensagens_nao_lidas)

@app.route('/admin/noticias')
def admin_noticias():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    noticias = Noticia.query.order_by(Noticia.criado_em.desc()).all()
    return render_template('admin/noticias.html', noticias=noticias)

@app.route('/admin/noticia/nova', methods=['GET', 'POST'])
def admin_nova_noticia():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    if request.method == 'POST':
        titulo = request.form.get('titulo')
        subtitulo = request.form.get('subtitulo')
        texto = request.form.get('texto')
        categoria = request.form.get('categoria')
        publicado = request.form.get('publicado') == 'on'
        
        imagem_capa = ''
        if 'imagem_capa' in request.files:
            file = request.files['imagem_capa']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                imagem_capa = filename
        
        noticia = Noticia(titulo=titulo, subtitulo=subtitulo, texto=texto, 
                         categoria=categoria, imagem_capa=imagem_capa, publicado=publicado)
        db.session.add(noticia)
        db.session.commit()
        
        flash('Notícia criada com sucesso!', 'success')
        return redirect(url_for('admin_noticias'))
    
    return render_template('admin/noticia_form.html', noticia=None)

@app.route('/admin/noticia/editar/<int:id>', methods=['GET', 'POST'])
def admin_editar_noticia(id):
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    noticia = Noticia.query.get_or_404(id)
    
    if request.method == 'POST':
        noticia.titulo = request.form.get('titulo')
        noticia.subtitulo = request.form.get('subtitulo')
        noticia.texto = request.form.get('texto')
        noticia.categoria = request.form.get('categoria')
        noticia.publicado = request.form.get('publicado') == 'on'
        
        if 'imagem_capa' in request.files:
            file = request.files['imagem_capa']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                noticia.imagem_capa = filename
        
        db.session.commit()
        flash('Notícia atualizada com sucesso!', 'success')
        return redirect(url_for('admin_noticias'))
    
    return render_template('admin/noticia_form.html', noticia=noticia)

@app.route('/admin/noticia/excluir/<int:id>')
def admin_excluir_noticia(id):
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    noticia = Noticia.query.get_or_404(id)
    db.session.delete(noticia)
    db.session.commit()
    flash('Notícia excluída com sucesso!', 'success')
    return redirect(url_for('admin_noticias'))

@app.route('/admin/acoes')
def admin_acoes():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    acoes = AcaoParlamentar.query.order_by(AcaoParlamentar.criado_em.desc()).all()
    return render_template('admin/acoes.html', acoes=acoes)

@app.route('/admin/acao/nova', methods=['GET', 'POST'])
def admin_nova_acao():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    if request.method == 'POST':
        titulo = request.form.get('titulo')
        descricao = request.form.get('descricao')
        tipo = request.form.get('tipo')
        categoria = request.form.get('categoria')
        status = request.form.get('status')
        data_protocolo_str = request.form.get('data_protocolo')
        
        data_protocolo = None
        if data_protocolo_str:
            try:
                data_protocolo = datetime.strptime(data_protocolo_str, '%Y-%m-%d').date()
            except:
                pass
        
        imagem = ''
        documento = ''
        
        if 'imagem' in request.files:
            file = request.files['imagem']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                imagem = filename
        
        if 'documento' in request.files:
            file = request.files['documento']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                documento = filename
        
        acao = AcaoParlamentar(titulo=titulo, descricao=descricao, tipo=tipo, 
                              categoria=categoria, status=status, imagem=imagem, 
                              documento=documento, data_protocolo=data_protocolo)
        db.session.add(acao)
        db.session.commit()
        
        flash('Ação parlamentar cadastrada com sucesso!', 'success')
        return redirect(url_for('admin_acoes'))
    
    tipos = ['Projeto de Lei', 'Indicação', 'Requerimento', 'Fiscalização', 'Pedido de Providência']
    categorias = ['Saúde', 'Educação', 'Infraestrutura', 'Assistência Social', 'Agricultura', 'Obras Públicas', 'Fiscalização']
    return render_template('admin/acao_form.html', acao=None, tipos=tipos, categorias=categorias)

@app.route('/admin/acao/editar/<int:id>', methods=['GET', 'POST'])
def admin_editar_acao(id):
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    acao = AcaoParlamentar.query.get_or_404(id)
    
    if request.method == 'POST':
        acao.titulo = request.form.get('titulo')
        acao.descricao = request.form.get('descricao')
        acao.tipo = request.form.get('tipo')
        acao.categoria = request.form.get('categoria')
        acao.status = request.form.get('status')
        
        data_protocolo_str = request.form.get('data_protocolo')
        if data_protocolo_str:
            try:
                acao.data_protocolo = datetime.strptime(data_protocolo_str, '%Y-%m-%d').date()
            except:
                pass
        
        if 'imagem' in request.files:
            file = request.files['imagem']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                acao.imagem = filename
        
        if 'documento' in request.files:
            file = request.files['documento']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                acao.documento = filename
        
        db.session.commit()
        flash('Ação atualizada com sucesso!', 'success')
        return redirect(url_for('admin_acoes'))
    
    tipos = ['Projeto de Lei', 'Indicação', 'Requerimento', 'Fiscalização', 'Pedido de Providência']
    categorias = ['Saúde', 'Educação', 'Infraestrutura', 'Assistência Social', 'Agricultura', 'Obras Públicas', 'Fiscalização']
    return render_template('admin/acao_form.html', acao=acao, tipos=tipos, categorias=categorias)

@app.route('/admin/acao/excluir/<int:id>')
def admin_excluir_acao(id):
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    acao = AcaoParlamentar.query.get_or_404(id)
    db.session.delete(acao)
    db.session.commit()
    flash('Ação excluída com sucesso!', 'success')
    return redirect(url_for('admin_acoes'))

@app.route('/admin/galeria')
def admin_galeria():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    fotos = FotoGaleria.query.order_by(FotoGaleria.criado_em.desc()).all()
    return render_template('admin/galeria.html', fotos=fotos)

@app.route('/admin/galeria/adicionar', methods=['GET', 'POST'])
def admin_adicionar_foto():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    if request.method == 'POST':
        titulo = request.form.get('titulo')
        descricao = request.form.get('descricao')
        categoria = request.form.get('categoria')
        destaque = request.form.get('destaque') == 'on'
        
        if 'imagem' in request.files:
            file = request.files['imagem']
            if file and file.filename:
                filename = str(uuid.uuid4()) + '_' + secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                
                foto = FotoGaleria(titulo=titulo, descricao=descricao, categoria=categoria,
                                  imagem=filename, destaque=destaque)
                db.session.add(foto)
                db.session.commit()
                flash('Foto adicionada com sucesso!', 'success')
                return redirect(url_for('admin_galeria'))
    
    return render_template('admin/galeria_form.html', foto=None)

@app.route('/admin/galeria/excluir/<int:id>')
def admin_excluir_foto(id):
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    foto = FotoGaleria.query.get_or_404(id)
    db.session.delete(foto)
    db.session.commit()
    flash('Foto excluída com sucesso!', 'success')
    return redirect(url_for('admin_galeria'))

@app.route('/admin/mensagens')
def admin_mensagens():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    mensagens = MensagemContato.query.order_by(MensagemContato.criado_em.desc()).all()
    return render_template('admin/mensagens.html', mensagens=mensagens)

@app.route('/admin/mensagem/marcar-lida/<int:id>')
def admin_marcar_mensagem_lida(id):
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    mensagem = MensagemContato.query.get_or_404(id)
    mensagem.lido = True
    db.session.commit()
    flash('Mensagem marcada como lida!', 'success')
    return redirect(url_for('admin_mensagens'))

@app.route('/admin/configuracoes', methods=['GET', 'POST'])
def admin_configuracoes():
    if 'admin_id' not in session:
        return redirect(url_for('admin_login'))
    
    if request.method == 'POST':
        configs = {
            'whatsapp_numero': request.form.get('whatsapp_numero'),
            'instagram_url': request.form.get('instagram_url'),
            'facebook_url': request.form.get('facebook_url'),
            'youtube_url': request.form.get('youtube_url'),
            'endereco': request.form.get('endereco'),
            'email_contato': request.form.get('email_contato')
        }
        
        for chave, valor in configs.items():
            config = Configuracao.query.filter_by(chave=chave).first()
            if config:
                config.valor = valor
            else:
                config = Configuracao(chave=chave, valor=valor)
                db.session.add(config)
        
        db.session.commit()
        flash('Configurações salvas com sucesso!', 'success')
        return redirect(url_for('admin_configuracoes'))
    
    configs = {}
    for c in Configuracao.query.all():
        configs[c.chave] = c.valor
    
    return render_template('admin/configuracoes.html', configs=configs)

# ==================== INICIALIZAÇÃO ====================

def criar_admin_padrao():
    admin = Admin.query.filter_by(email='admin@vereadordodo.com.br').first()
    if not admin:
        admin = Admin(
            nome='Administrador',
            email='admin@vereadordodo.com.br',
            senha_hash=generate_password_hash('admin123')
        )
        db.session.add(admin)
        db.session.commit()
        print("Admin padrão criado: admin@vereadordodo.com.br / admin123")

def criar_configs_padrao():
    configs_padrao = {
        'whatsapp_numero': '73999999999',
        'instagram_url': 'https://instagram.com/vereadordodo',
        'facebook_url': 'https://facebook.com/vereadordodo',
        'youtube_url': 'https://youtube.com/@vereadordodo',
        'endereco': 'Câmara Municipal de Mucuri - BA',
        'email_contato': 'contato@vereadordodo.com.br'
    }
    
    for chave, valor in configs_padrao.items():
        config = Configuracao.query.filter_by(chave=chave).first()
        if not config:
            config = Configuracao(chave=chave, valor=valor)
            db.session.add(config)
    
    db.session.commit()

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
        criar_admin_padrao()
        criar_configs_padrao()
    
    print("=" * 60)
    print("🏛️ SITE DO VEREADOR DOUGLAS SOUTO (DODÔ)")
    print("=" * 60)
    print("🌐 Acesse: http://localhost:5000")
    print("🔐 Admin: http://localhost:5000/admin/login")
    print("   Usuário: admin@vereadordodo.com.br")
    print("   Senha: admin123")
    print("=" * 60)
    
    app.run(host='0.0.0.0', port=5000, debug=True)
