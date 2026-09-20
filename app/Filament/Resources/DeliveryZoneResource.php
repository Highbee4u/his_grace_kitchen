<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryZoneResource\Pages;
use App\Models\DeliveryZone;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DeliveryZoneResource extends Resource
{
    protected static ?string $model = DeliveryZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Logistics & Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('e.g. Lagos Island Express')
                    ->required(),
                Forms\Components\TextInput::make('fee_minor')
                    ->label('Delivery Fee in Minor Units (e.g. 350000 = ₦3,500)')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('currency')
                    ->options([
                        'NGN' => 'NGN (₦)',
                        'GBP' => 'GBP (£)',
                        'USD' => 'USD ($)',
                        'CAD' => 'CAD ($)',
                        'EUR' => 'EUR (€)',
                    ])
                    ->default('NGN')
                    ->required(),
                Forms\Components\TextInput::make('lead_time_minutes')
                    ->label('Estimated Delivery Lead Time (Minutes)')
                    ->required()
                    ->numeric()
                    ->default(60),
                Forms\Components\TagsInput::make('areas')
                    ->label('Covered Neighborhoods / Areas')
                    ->placeholder('Add area name and press Enter')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('allow_pay_on_delivery')
                    ->label('Allow Cash / POS on Delivery in this zone')
                    ->default(false),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active for checkout')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('fee_minor')
                    ->label('Fee')
                    ->formatStateUsing(fn ($state, DeliveryZone $record) => Money::format($state, $record->currency))
                    ->sortable(),
                Tables\Columns\TextColumn::make('lead_time_minutes')
                    ->label('Lead Time')
                    ->formatStateUsing(fn ($state) => $state >= 60 ? round($state / 60, 1).' hrs' : $state.' mins')
                    ->sortable(),
                Tables\Columns\IconColumn::make('allow_pay_on_delivery')
                    ->label('Pay On Delivery')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveryZones::route('/'),
            'create' => Pages\CreateDeliveryZone::route('/create'),
            'edit' => Pages\EditDeliveryZone::route('/{record}/edit'),
        ];
    }
}
