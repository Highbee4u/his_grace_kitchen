<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboResource\Pages;
use App\Models\Combo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ComboResource extends Resource
{
    protected static ?string $model = Combo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Menu & Catalog';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Combo Bundle Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(Combo::class, 'slug', ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price_minor')
                            ->label('Bundle Price in Minor Units (e.g. 750000 = ₦7,500)')
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
                        Forms\Components\TextInput::make('image_url')
                            ->label('Image URL')
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('items')
                            ->label('Included Items')
                            ->placeholder('Add item name and press Enter')
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('customisation_slots')
                            ->label('Customizable Choice Slots')
                            ->placeholder('e.g. Protein Choice, Drink Choice')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Price')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('price_minor', $direction)),
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
            'index' => Pages\ListCombos::route('/'),
            'create' => Pages\CreateCombo::route('/create'),
            'edit' => Pages\EditCombo::route('/{record}/edit'),
        ];
    }
}
