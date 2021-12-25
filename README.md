# ECサイト Bi-Scouter(在庫管理ツール)

[![IMAGE ALT TEXT HERE](thumbnailImage.png)](https://youtu.be/xwcmHFuv7Jo)

### 概要
* 制作期間： 14日間
* 使用技術： HTML,CSS,JS,Laravel,MySQL,GoogleSheetAPI
* プロダクト紹介映像: https://youtu.be/xwcmHFuv7Jo

[棚卸しツール](https://github.com/worldwideweb13/stock-cal)をバージョンアップしたもの。
ECサイト運営者向けの売上／在庫管理ツールを作成しました。本アプリではGoogleスプレッドシート、CSVファイルからデータを取り込み、売上集計、在庫管理機能を提供します。

### 実行環境
* php 7.4.2
* MySql 5.7.26
* MAMP推奨

DBは[gs_db.sql](gs_db.sql)をMySqlにインポートして下さい。テストデータが既に含まれているデータファルになります。
アプリの利用手順は以下になります。(※sqlデータには全てのデータがセットされているため、スプレッドシート、csvファイルの読み込みは不要です)
1. ログイン([実行環境/php/login.php](php/login.php))後、GoogleSpreadシートからデータを取り込み
2. Top画面にて取り込まれたデータ確認、在庫編集機能の利用
3. CSVファイルから売上データ取り込み
4. 売上集計機能から開始と終了日付を入力すると、期間内の売上データが閲覧。

### 開発の苦労した点
GoogleスプレッドシートのAPI連携、非同期処理、DBからデータ取得と表示など、MVCモデルに沿ってコードを書く一連の処理の流れを掴むことができました。
