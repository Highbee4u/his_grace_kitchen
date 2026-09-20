<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $navigationGroup = 'Menu & Catalog';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dish Details')
                    ->schema([
                        Forms\Components\Select::make('menu_category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(MenuItem::class, 'slug', ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('image_url')
                            ->label('Image URL / Photography Link')
                            ->placeholder('https://images.unsplash.com/...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        Forms\Components\TextInput::make('price_minor')
                            ->label('Price in Minor Units (e.g. 450000 = ₦4,500)')
                            ->helperText('100 units = 1.00 currency unit')
                            ->required()
                            ->numeric()
                            ->default(450000),
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
                        Forms\Components\TextInput::make('daily_limit')
                            ->label('Daily Limit (Optional)')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\Toggle::make('is_available')
                            ->label('Available for ordering')
                            ->default(true),
                        Forms\Components\Toggle::make('is_vegetarian')
                            ->label('Vegetarian Dish')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Dietary & Flavor Profile')
                    ->schema([
                        Forms\Components\Select::make('spice_level')
                            ->options([
                                'None' => 'None (No heat)',
                                'Mild' => 'Mild 🌶️',
                                'Medium' => 'Medium 🌶️🌶️',
                                'Hot' => 'Hot 🌶️🌶️🌶️',
                                'Extra Hot' => 'Extra Hot 🔥',
                            ])
                            ->default('Medium'),
                        Forms\Components\TagsInput::make('allergens')
                            ->placeholder('e.g. Peanuts, Fish, Gluten, Dairy'),
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('e.g. Popular, Signature, Spicy, Chef Special'),
                    ])->columns(3),
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
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Price')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('price_minor', $direction)),
                Tables\Columns\TextColumn::make('spice_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hot', 'Extra Hot' => 'danger',
                        'Medium' => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_vegetarian')
                    ->label('Veg')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu_category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Availability'),
                Tables\Filters\SelectFilter::make('spice_level')
                    ->options([
                        'None' => 'None',
                        'Mild' => 'Mild',
                        'Medium' => 'Medium',
                        'Hot' => 'Hot',
                        'Extra Hot' => 'Extra Hot',
                    ]),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
