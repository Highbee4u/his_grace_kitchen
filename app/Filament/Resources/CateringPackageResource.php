<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CateringPackageResource\Pages;
use App\Models\CateringPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CateringPackageResource extends Resource
{
    protected static ?string $model = CateringPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Catering & Custom Requests';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(CateringPackage::class, 'slug', ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('pricing_model')
                            ->options([
                                'per_head' => 'Per Guest / Per Head',
                                'fixed_package' => 'Fixed Package Price',
                            ])
                            ->default('per_head')
                            ->required(),
                        Forms\Components\TextInput::make('price_minor')
                            ->label('Price in Minor Units (e.g. 1800000 = ₦18,000)')
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
                        Forms\Components\TextInput::make('image_url')
                            ->label('Image URL')
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('includes')
                            ->label('Package Inclusions & Features')
                            ->placeholder('e.g. Live Suya Station, Jollof Rice, Chafing Sets')
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
                Tables\Columns\TextColumn::make('pricing_model')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state === 'per_head' ? 'Per Guest' : 'Fixed'),
                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Rate')
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
            'index' => Pages\ListCateringPackages::route('/'),
            'create' => Pages\CreateCateringPackage::route('/create'),
            'edit' => Pages\EditCateringPackage::route('/{record}/edit'),
        ];
    }
}
