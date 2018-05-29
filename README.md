# Bet_app
Bet app

# Install
```
git clone https://github.com/BenasG/Bet_app.git

php artisan migrate
```

# Usage
Insert implemented betslip
```
curl http://[localhost]/bet/create
```
Insert your betslip
```
$betslip = [
    'player_id' => 1,
    'stake_amount' => 10,
    'errors' => [],
    'selections' => [
        [
            'id' => 1,
            'odds' => 1.601,
            'errors' => [],
        ],
        [
            'id' => 2,
            'odds' => 1.601,
            'errors' => [],
        ],
    ],
];

curl -X GET -d "player_id=1&stake_amount=10&selections%5B0%5D%5Bid%5D=1&selections%5B0%5D%5Bodds%5D=1.601&selections%5B1%5D%5Bid%5D=2&selections%5B1%5D%5Bodds%5D=1.601" http://192.168.10.10/bet/create
```
