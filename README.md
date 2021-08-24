# 使用技術  
- MDBootstrap
- MailHog : メールテストツール  
- Sendgrid : メールサービス
- Vue Tags Input : vueライブラリでタグを扱うのに役立つ(タグの自動補完付き)  
入力済みタグのPHPは、spanタグで囲まれてPOST送信できないので、hiddenタグでタグ情報を送信。
- アクセサ使用
- OAuth : 認可の仕組み。googleアカウントを使用できるようにするために使用。
- Socialite (ver2.6) : Googleなどの他サービスのアカウントを使ったログイン機能を比較的簡単に実装することができる。  
インストール `composer require laravel/socialite`  
/config/app.php/でprovidersに`Intervention\Image\ImageServiceProvider::class, `を追記。  
aliasesにも登録。
- N+1問題の解消  
- Intervention Image : PHPで画像のリサイズをするために使用  
`composer require intervention/image`  
- ポリシー使用  
[参考](https://www.techpit.jp/courses/11/curriculums/12/sections/111/parts/411)  
[公式](https://readouble.com/laravel/8.x/ja/authorization.html#registering-policies)
- 投稿したユーザー以外は、記事の編集画面を開けないようにしている。ArticlePolicyにより、ログインしていないとポリシーにより、403エラーになるので、引数に?を入れてnullable型を使用して、nullも許容する。  
- 新しい記法で書かれたJavaScriptを各ブラウザで動かせる形式にトランスパイル(変換)する仕組みとして、Laravel Mixというものが用意されている。(webpack.mix.js)  
(公式)[https://readouble.com/laravel/6.x/ja/mix.html#versioning-and-cache-busting]


# DB  
- tags : タグの名前を管理するタグテーブル
- article_tag : 「どの記事に」「何のタグが」付いているかを管理するテーブル
- follows : 「どのユーザーが」「どのユーザーを」フォローしているかを管理するテーブル


# TODO  
- [ ] loggedOutメソッドをオーバーライドして、ログアウト時に何かしらの処理をする。  
- [ ] ポリシーを使わずに、投稿したユーザー以外は、記事の編集画面を開けないようにする。  
- [ ] ポリシーでAuthモデルを試す。なぜUserモデル？  
- [x] プルダウン不具合[https://www.techpit.jp/courses/11/curriculums/12/sections/113/parts/424]  pacckege.jsonとapp.blade.phpとapp.jsにあるから？  
-> app.jsのbootstrap読み込むところをコメントアウトで解決
- [X] 多対多のリレーションで取得できない。  
-> 指定した引数が間違っていた。
- [ ] 全てのタグを取得するallTagNamesを共通メソッドにする。
- [ ] タグテーブルに登録済みのタグ数が膨大になってきた場合は、ある程度絞った方が良い。どのようなタグを優先するか、またそれをどのように絞り込むかを考える。
- [ ] フォローボタンが押されたと同時にフォロワー数表示を変化させる  
- [ ] リレーション部分をinner joinを使ってSQLを書いてみる  
- [ ] 現在ログイン中のユーザーを表示
- [ ] ユーザーモデルに画像追加 (画像アップロード前にプレビュー機能)[https://www.techpit.jp/courses/42/curriculums/45/sections/362/parts/1148]


# memo  
- `npm run watch-poll`  
- Vue.jsでは、親コンポーネント(ここではBlade)からpropsへ渡されたプロパティの値を、子のコンポーネント側で変更することは推奨されていません。  
- アクセサ (getCountLikesAttribute())[https://www.techpit.jp/courses/11/curriculums/12/sections/113/parts/425]  
- 既にtagsテーブルに存在するタグであれば、tagsテーブルに登録する必要は無く、記事とタグの紐付けのみを行えば良い(article_tagテーブルにレコードを保存するだけで良い)ことになります。(article_tagは中間テーブル)  
そこで、firstOrCreateメソッドを使う。firstOrCreateメソッドは、引数として渡した「カラム名と値のペア」を持つレコードがテーブルに存在するかどうかを探し、もし存在すればそのモデルを返します。テーブルに存在しなければ、そのレコードをテーブルに保存した上で、モデルを返します。
- DBの関係が多対多の場合は、中間テーブルに紐付けるのに`attach()`メソッドを使う。紐付けを解除するには`detach()`を使う。
