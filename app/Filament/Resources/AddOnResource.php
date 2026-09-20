<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddOnResource\Pages;
use App\Models\AddOn;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AddOnResource extends Resource
{
    protected static ?string $model = AddOn::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    protected static ?string $navigationGroup = 'Menu & Catalog';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('price_minor')
                    ->label('Price in Minor Units (e.g. 80000 = ₦800)')
                    ->required()
                    ->numeric(),
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
                Forms\Components\Toggle::make('is_available')
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
                Tables\Columns\TextColumn::make('price_minor')
                    ->label('Price')
                    ->formatStateUsing(fn ($state, $record) => Money::format($state, $record->currency))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
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
            'index' => Pages\ListAddOns::route('/'),
            'create' => Pages\CreateAddOn::route('/create'),
            'edit' => Pages\EditAddOn::route('/{record}/edit'),
        ];
    }
}
