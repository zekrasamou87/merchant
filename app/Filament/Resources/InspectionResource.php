<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionResource\Pages;
use App\Models\Inspection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InspectionResource extends Resource
{
    protected static ?string $model = Inspection::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'الزيارات الميدانية';
    protected static ?string $modelLabel = 'زيارة ميدانية';
    protected static ?string $pluralModelLabel = 'الزيارات الميدانية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تفاصيل الكشف الميداني')
                    ->schema([
                        Forms\Components\Select::make('store_id')
                            ->label('المحل المستهدف')
                            ->relationship('store', 'store_name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('inspectors')
                            ->label('فريق المراقبين المشاركين')
                            ->relationship('inspectors', 'full_name')
                            ->multiple() // اختيار أكثر من مراقب
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('inspection_date')
                            ->label('تاريخ ووقت الزيارة')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('result')
                            ->label('نتيجة التقييم')
                            ->options([
                                'committed' => 'ملتزم ✅',
                                'needs_correction' => 'بحاجة تصحيح ⚠️',
                                'violating' => 'مخالف ❌',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('rating_score') // الحقل المفقود
    ->label('تقييم الزيارة (0-100)')
    ->numeric()
    ->minValue(0)
    ->maxValue(100)
    ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات الفريق')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store.store_name')
                    ->label('المحل')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('inspection_date')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('result')
                    ->label('النتيجة')
                    ->colors([
                        'success' => 'committed',
                        'warning' => 'needs_correction',
                        'danger' => 'violating',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'committed' => 'ملتزم',
                        'needs_correction' => 'بحاجة تصحيح',
                        'violating' => 'مخالف',
                    }),

                Tables\Columns\TextColumn::make('inspectors.full_name')
                    ->label('فريق المراقبين')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->label('تصفية حسب النتيجة')
                    ->options([
                        'committed' => 'ملتزم',
                        'needs_correction' => 'بحاجة تصحيح',
                        'violating' => 'مخالف',
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInspections::route('/'),
            'create' => Pages\CreateInspection::route('/create'),
            'edit' => Pages\EditInspection::route('/{record}/edit'),
        ];
    }
}