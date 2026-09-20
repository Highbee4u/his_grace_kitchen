<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Marketing & Reviews';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer & Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Full Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_location')
                            ->label('Location (e.g. Lekki Phase 1, Lagos or London, UK)')
                            ->maxLength(255),
                        Forms\Components\Select::make('user_id')
                            ->label('Registered User (Optional)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('order_id')
                            ->label('Linked Order Number')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Toggle::make('is_verified_buyer')
                            ->label('Verified Dining Customer')
                            ->default(false),
                    ])->columns(3),

                Forms\Components\Section::make('Review Content & Ratings')
                    ->schema([
                        Forms\Components\Select::make('rating')
                            ->label('Star Rating')
                            ->options([
                                5 => '★★★★★ (5 Stars - Outstanding)',
                                4 => '★★★★☆ (4 Stars - Great)',
                                3 => '★★★☆☆ (3 Stars - Average)',
                                2 => '★★☆☆☆ (2 Stars - Below Average)',
                                1 => '★☆☆☆☆ (1 Star - Poor)',
                            ])
                            ->required()
                            ->default(5),
                        Forms\Components\TextInput::make('title')
                            ->label('Review Headline / Title')
                            ->placeholder('e.g. Best party jollof in town! Authentic firewood smoky flavor.')
                            ->maxLength(255),
                        Forms\Components\Select::make('menu_item_id')
                            ->label('Catalog Menu Item (Optional)')
                            ->relationship('menuItem', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('dish_name')
                            ->label('Custom Dish / Experience Name')
                            ->placeholder('e.g. Smoky Party Jollof & Dodo or Full Catering Setup')
                            ->helperText('Used if dish is not linked to catalog or for custom experiences'),
                        Forms\Components\Textarea::make('comment')
                            ->label('Customer Review / Comment')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Moderation & Kitchen Reply')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Moderation Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved (Live on Website)',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('approved'),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Feature on Homepage Testimonials')
                            ->helperText('Highlight this review in the homepage testimonial section')
                            ->default(false),
                        Forms\Components\Textarea::make('admin_response')
                            ->label('Official Kitchen / Chef Response')
                            ->placeholder('e.g. Thank you so much for your kind words! We are glad you loved the woodsmoke flavor...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stars')
                    ->label('Rating')
                    ->color('warning')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('rating', $direction)),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_location')
                    ->label('Location')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('dish_title')
                    ->label('Dish / Experience')
                    ->searchable(query: fn ($query, $search) => $query->where('dish_name', 'like', "%{$search}%")->orWhereHas('menuItem', fn ($q) => $q->where('name', 'like', "%{$search}%"))),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Review')
                    ->limit(45)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_verified_buyer')
                    ->label('Verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                        3 => '3 Stars',
                        2 => '2 Stars',
                        1 => '1 Star',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
                Tables\Filters\TernaryFilter::make('is_verified_buyer')
                    ->label('Verified Buyers'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Review $record): bool => $record->status !== 'approved')
                    ->action(fn (Review $record) => $record->update(['status' => 'approved'])),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Review $record): bool => $record->status !== 'rejected')
                    ->action(fn (Review $record) => $record->update(['status' => 'rejected'])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approveAll')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'approved'])),
                    Tables\Actions\BulkAction::make('rejectAll')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'rejected'])),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
